<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\CalendarEvent;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\IncidentReport;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password as PasswordRule;

class EmployeesController extends Controller
{
    /** Show the employee login page (redirect straight to dashboard if already signed in). */
    public function employee_login()
    {
        if (Auth::check() && Auth::user()->hasRole('employee')) {
            return redirect()->route('frontend.employee_dashboard');
        }

        return view('frontend.employee.login');
    }

    /** Handle the employee login form submission. */
    public function authenticate(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required'    => 'Please enter your email address.',
            'email.email'       => 'Please enter a valid email address.',
            'password.required' => 'Please enter your password.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']], $remember)) {
            $user = Auth::user();

            // Only employee-role accounts may use this portal.
            if (! $user->hasRole('employee')) {
                Auth::logout();
                return back()->withInput($request->only('email'))
                    ->withErrors(['email' => 'This login is for employees only. Please use the admin login.']);
            }

            if (! $user->is_active) {
                Auth::logout();
                return back()->withInput($request->only('email'))
                    ->withErrors(['email' => 'Your account is inactive. Please contact your administrator.']);
            }

            $request->session()->regenerate();
            // Remember the password hash so we can detect a later password change.
            $request->session()->put('employee_password_hash', $user->password);

            return redirect()->route('frontend.employee_dashboard')->with('message', 'Welcome back, '.$user->name.'!');
        }

        return back()->withInput($request->only('email'))
            ->withErrors(['email' => 'The email or password you entered is incorrect.']);
    }

    /** Show the employee forgot-password page. */
    public function employee_forgot_password()
    {
        return view('frontend.employee.employee_forgot_password');
    }

    /** Email a password-reset link to the employee. */
    public function send_reset_link(Request $request)
    {
        $request->validate(['email' => 'required|email'], [
            'email.required' => 'Please enter your email address.',
        ]);

        // Only employee accounts can reset through this portal.
        $user = User::where('email', $request->email)->first();
        if (! $user || ! $user->hasRole('employee')) {
            return back()->withInput()->with('error', 'No employee account was found with that email address.');
        }

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('message', 'We have emailed you a password reset link. Please check your inbox.')
            : back()->withInput()->with('error', 'Sorry, we could not send a reset link right now. Please try again in a moment.');
    }

    /** Show the reset-password form (opened from the emailed link). */
    public function employee_reset_password(Request $request, string $token)
    {
        return view('frontend.employee.employee_reset_password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    /** Set the new password using the reset token. */
    public function update_password(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->setRememberToken(Str::random(60));
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('frontend.employee_login')
                ->with('message', 'Your password has been reset successfully. Please sign in with your new password.');
        }

        // Friendly, non-technical messages a layman user can understand.
        $friendly = match ($status) {
            Password::INVALID_TOKEN   => 'This password reset link has already been used or has expired. Please request a new one.',
            Password::INVALID_USER    => 'We could not find an account with that email address.',
            Password::RESET_THROTTLED => 'You have tried too many times. Please wait a moment and try again.',
            default                   => 'Sorry, we could not reset your password. Please request a new reset link.',
        };

        // If the link is invalid/used/expired, send them to the forgot page to request a fresh one.
        if ($status === Password::INVALID_TOKEN) {
            return redirect()->route('frontend.employee_forgot_password')->with('error', $friendly);
        }

        return back()->withInput($request->only('email'))->with('error', $friendly);
    }

    /** Public portal landing page — the common dashboard, no login required. */
    public function employee_portal()
    {
        return view('frontend.employee.dashboard', [
            'employee'      => Auth::user(), // may be null when browsing logged-out
            'announcements' => Announcement::publishedLatest()->limit(3)->get(),
        ]);
    }

    /** Personalised employee dashboard (guarded by the employee.auth middleware). */
    public function employee_dashboard()
    {
        $user = Auth::user();

        $base = IncidentReport::where('reported_by', $user->id);

        return view('frontend.employee.home', [
            'employee'      => $user,
            'announcements' => Announcement::publishedLatest()->limit(3)->get(),
            'myReports'     => (clone $base)->latest()->limit(5)->get(),
            'myReportsAll'  => (clone $base)->with('photos')->latest()->get(),
            'reportStats'   => [
                'total'  => (clone $base)->count(),
                'open'   => (clone $base)->where('status', 'open')->count(),
                'review' => (clone $base)->where('status', 'under-review')->count(),
                'closed' => (clone $base)->where('status', 'closed')->count(),
            ],
        ]);
    }

    /** Full list of published announcements for employees. */
    public function employee_announcements()
    {
        return view('frontend.employee.announcements', [
            'announcements' => Announcement::publishedLatest()->paginate(10),
        ]);
    }

    /** Read a single published announcement. */
    public function employee_announcement(string $slug)
    {
        $announcement = Announcement::where('slug', $slug)->where('is_active', true)->firstOrFail();

        return view('frontend.employee.announcement_show', ['announcement' => $announcement]);
    }

    /** Show the employee-facing incident report form. */
    public function employee_incident_report()
    {
        return view('frontend.employee.employee_incident_report', [
            'categories' => IncidentReport::CATEGORIES,
            'severities' => IncidentReport::SEVERITIES,
            'employees'  => $this->activeEmployees(),
        ]);
    }

    /** Active employees (excluding the current user) for the "employee involved" dropdown. */
    private function activeEmployees()
    {
        return User::whereHas('role', fn ($q) => $q->where('slug', 'employee'))
            ->where('is_active', true)
            ->where('id', '!=', Auth::id())
            ->orderBy('name')
            ->get();
    }

    /** Save an incident report submitted by the employee. */
    public function employee_incident_report_store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'employee_id'        => 'nullable|exists:users,id',
            'incident_date'      => 'required|date|before_or_equal:today',
            'incident_time'      => 'nullable|date_format:H:i',
            'incident_location'  => 'required|string|min:3|max:255',
            'category'           => ['required', Rule::in(array_keys(IncidentReport::CATEGORIES))],
            'severity'           => ['required', Rule::in(array_keys(IncidentReport::SEVERITIES))],
            'description'        => 'required|string|min:10',
            'immediate_action'   => 'nullable|string',
            'witnesses'          => 'nullable|string|max:255',
            'incident_photos'    => 'nullable|array|max:6',
            'incident_photos.*'  => 'image|mimes:jpg,jpeg,png,gif,webp|max:'.config('uploads.image_max_kb'),
        ], [
            'incident_date.before_or_equal' => 'The incident date cannot be in the future.',
            'incident_location.min'         => 'Location must be at least 3 characters.',
            'description.min'               => 'Description must be at least 10 characters.',
            'incident_photos.max'           => 'You can upload up to 6 photos at a time.',
            'incident_photos.*.image'       => 'Each attachment must be an image (JPG, PNG, GIF or WEBP).',
            'incident_photos.*.max'         => 'Each image may not be larger than '.round(config('uploads.image_max_kb') / 1024).' MB.',
        ]);

        // reported_by = the employee filing the report (always the logged-in user).
        // employee_id = the employee actually involved — defaults to the reporter,
        // but they can pick a colleague from the dropdown. Both are real user FKs,
        // so the full employee record (name, email, role) is captured either way.
        // source = 'employee' flags this as a portal submission (vs. admin-created).
        $involvedId = $validated['employee_id'] ?? $user->id;

        $report = IncidentReport::create([
            'reported_by'      => $user->id,
            'employee_id'      => $involvedId,
            'reporter_name'    => $user->name,
            'incident_date'    => $validated['incident_date'],
            'incident_time'    => $validated['incident_time'] ?? null,
            'location'         => $validated['incident_location'],
            'category'         => $validated['category'],
            'severity'         => $validated['severity'],
            'description'      => $validated['description'],
            'immediate_action' => $validated['immediate_action'] ?? null,
            'witnesses'        => $validated['witnesses'] ?? null,
            'status'           => 'open',
            'source'           => 'employee',
            'created_by'       => $user->id,
        ]);

        $report->reference_no = 'IR-'.$report->created_at->format('Y').'-'.str_pad((string) $report->id, 4, '0', STR_PAD_LEFT);
        $report->save();

        // Store any uploaded photos.
        if ($request->hasFile('incident_photos')) {
            $dir = public_path('uploads/incident-reports');
            if (! is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }
            foreach ($request->file('incident_photos') as $file) {
                if (! $file->isValid()) {
                    continue;
                }
                $base = preg_replace('/[^A-Za-z0-9_\-]/', '', preg_replace('/\s+/', '_', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))) ?: 'photo';
                $original = $file->getClientOriginalName();
                $size     = $file->getSize();
                $mime     = $file->getClientMimeType();
                $filename = $base.'_'.time().'_'.mt_rand(100, 999).'.'.$file->getClientOriginalExtension();
                $file->move($dir, $filename);
                $report->photos()->create([
                    'file_path'     => 'uploads/incident-reports/'.$filename,
                    'original_name' => $original,
                    'file_size'     => $size,
                    'mime_type'     => $mime,
                ]);
            }
        }

        return redirect()->route('frontend.employee_incident_report_thankyou')
            ->with('reference', $report->reference_no);
    }

    /** Thank-you page shown after an incident report is submitted. */
    public function employee_incident_report_thankyou()
    {
        // Only reachable straight after a submission (reference is flashed to the session).
        $reference = session('reference');
        if (! $reference) {
            return redirect()->route('frontend.employee_incident_report');
        }

        return view('frontend.employee.incident_report_thankyou', ['reference' => $reference]);
    }

    /**
     * Document Library — categories split into Public (shared) and Personal spaces.
     * Public docs are visible to everyone; personal docs only when logged in.
     */
    public function employee_documents()
    {
        $user = Auth::user(); // may be null (browsing logged-out)

        $categories = DocumentCategory::where('is_active', true)
            ->withCount([
                'documents as public_count'   => fn ($q) => $q->where('is_public', true),
                'documents as personal_count' => fn ($q) => $user
                    ? $q->where('is_public', false)->where('user_id', $user->id)
                    : $q->whereRaw('0 = 1'),
            ])
            ->orderBy('name')
            ->get();

        return view('frontend.employee.documents.index', [
            'publicCategories'   => $categories->where('public_count', '>', 0)->values(),
            'personalCategories' => $categories->where('personal_count', '>', 0)->values(),
        ]);
    }

    /**
     * Show the documents inside a category. The `space` query param scopes the
     * view to what the user clicked: 'public' (shared only) or 'personal' (own only).
     * Personal docs are only ever included for the logged-in owner.
     */
    public function employee_document_category(string $slug, ?string $space = null)
    {
        $user = Auth::user(); // $space is 'public' | 'personal' | null (from the URL)

        $category = DocumentCategory::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $documents = $category->documents()
            ->where(function ($q) use ($user) {
                $q->where('is_public', true);
                if ($user) {
                    $q->orWhere('user_id', $user->id);
                }
            })
            ->orderByDesc('id')
            ->get();

        $publicDocs   = $documents->where('is_public', true)->values();
        $personalDocs = $documents->where('is_public', false)->values();

        // Narrow to just the space the user came from.
        if ($space === 'public') {
            $personalDocs = collect();
        } elseif ($space === 'personal') {
            $publicDocs = collect();
        }

        return view('frontend.employee.documents.category', [
            'category'     => $category,
            'publicDocs'   => $publicDocs,
            'personalDocs' => $personalDocs,
            'space'        => $space,
        ]);
    }

    /** Stream a document download. Public docs are open; personal docs need the owner logged in. */
    public function employee_document_download(Document $document)
    {
        if (! $document->is_public) {
            $user = Auth::user();
            if (! $user || (int) $document->user_id !== (int) $user->id) {
                abort(403, 'You do not have access to this document.');
            }
        }

        $full = public_path($document->file_path);
        if (! file_exists($full)) {
            abort(404, 'File not found.');
        }

        return response()->download($full, $document->original_name ?: basename($document->file_path));
    }

    /** Community Calendar — read-only view for everyone. */
    public function employee_calendar()
    {
        $today = now()->startOfDay();

        $upcoming = CalendarEvent::where('is_active', true)
            ->where(function ($q) use ($today) {
                $q->whereDate('start_date', '>=', $today)
                  ->orWhereDate('end_date', '>=', $today);
            })
            ->orderBy('start_date')
            ->orderBy('start_time')
            ->limit(8)
            ->get();

        return view('frontend.employee.calendar', [
            'categories' => CalendarEvent::CATEGORIES,
            'upcoming'   => $upcoming,
        ]);
    }

    /** JSON feed for the frontend FullCalendar (active events only). */
    public function employee_calendar_events()
    {
        $data = CalendarEvent::where('is_active', true)->get()->map(function (CalendarEvent $e) {
            $allDay = $e->all_day;

            if ($allDay) {
                $start = $e->start_date->toDateString();
                $end   = $e->end_date ? $e->end_date->copy()->addDay()->toDateString() : null; // FullCalendar end is exclusive
            } else {
                $start   = $e->start_date->toDateString().($e->start_time ? 'T'.$e->start_time : '');
                $endDate = $e->end_date ?: $e->start_date;
                $end     = $e->end_time ? $endDate->toDateString().'T'.$e->end_time : null;
            }

            // Friendly labels for the details popup.
            $dateLabel = $e->start_date->format('D, M j, Y');
            if ($e->end_date && $e->end_date->ne($e->start_date)) {
                $dateLabel .= ' – '.$e->end_date->format('D, M j, Y');
            }

            $timeLabel = 'All day';
            if (! $allDay && $e->start_time) {
                $timeLabel = \Illuminate\Support\Carbon::parse($e->start_time)->format('g:i A');
                if ($e->end_time) {
                    $timeLabel .= ' – '.\Illuminate\Support\Carbon::parse($e->end_time)->format('g:i A');
                }
            }

            return [
                'id'      => $e->id,
                'title'   => $e->title,
                'start'   => $start,
                'end'     => $end,
                'allDay'  => $allDay,
                'color'   => $e->color,
                'extendedProps' => [
                    'category'    => $e->category_label,
                    'color'       => $e->color,
                    'location'    => $e->location,
                    'description' => $e->description ? trim(strip_tags($e->description)) : null,
                    'dateLabel'   => $dateLabel,
                    'timeLabel'   => $timeLabel,
                ],
            ];
        });

        return response()->json($data);
    }

    /** Update the logged-in employee's profile (name, phone, avatar). Email stays fixed. */
    public function employee_update_profile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'phone'         => 'nullable|string|max:20',
            'avatar'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:'.config('uploads.image_max_kb'),
            'remove_avatar' => 'nullable|boolean',
        ], [
            'avatar.image' => 'The profile picture must be an image (JPG, PNG or WEBP).',
            'avatar.max'   => 'The profile picture may not be larger than '.round(config('uploads.image_max_kb') / 1024).' MB.',
        ]);

        $user->name  = $validated['name'];
        $user->phone = $validated['phone'] ?? null;

        if ($request->boolean('remove_avatar') && ! $request->hasFile('avatar')) {
            $this->deleteAvatar($user->avatar);
            $user->avatar = null;
        }

        if ($request->hasFile('avatar')) {
            $this->deleteAvatar($user->avatar);
            $user->avatar = $this->storeAvatar($request->file('avatar'));
        }

        $user->save();

        return redirect()->to(route('frontend.employee_dashboard').'#profile')
            ->with('message', 'Your profile has been updated successfully.');
    }

    /** Change the logged-in employee's password (verifying the current one). */
    public function employee_change_password(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password:web'],
            'password'         => ['required', 'confirmed', PasswordRule::min(8)],
        ], [
            'current_password.required'          => 'Please enter your current password.',
            'current_password.current_password'  => 'Your current password is incorrect.',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->setRememberToken(Str::random(60));
        $user->save();

        // Keep the session hash in sync so EmployeeAuth doesn't log us out mid-request.
        $request->session()->put('employee_password_hash', $user->password);

        return redirect()->to(route('frontend.employee_dashboard').'#profile')
            ->with('message', 'Your password has been changed successfully.');
    }

    /** Move an uploaded avatar into public/uploads/avatars with a safe, unique name. */
    private function storeAvatar(UploadedFile $file): string
    {
        $dir = public_path('uploads/avatars');
        if (! is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        $base = preg_replace('/[^A-Za-z0-9_\-]/', '', preg_replace('/\s+/', '_', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))) ?: 'avatar';
        $filename = $base.'_'.time().'_'.mt_rand(1000, 9999).'.'.$file->getClientOriginalExtension();
        $file->move($dir, $filename);

        return 'uploads/avatars/'.$filename;
    }

    private function deleteAvatar(?string $path): void
    {
        if ($path && file_exists(public_path($path))) {
            @unlink(public_path($path));
        }
    }

    /** Log the employee out. */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('frontend.employee_portal')->with('message', 'You have been logged out.');
    }
}
