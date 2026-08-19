<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\IncidentReport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

class IncidentReportController extends Controller
{
    /**
     * Whether the current user can manage all reports (admins). Employees only
     * hold view+create, so they are scoped to their own reports.
     */
    private function canManage(Request $request): bool
    {
        return (bool) $request->user()?->hasPermission('incident-reports.edit');
    }

    /** Employees available in the "employee involved" dropdown. */
    private function employees()
    {
        return User::whereHas('role', fn ($q) => $q->where('slug', 'employee'))
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function index(Request $request)
    {
        $query = IncidentReport::with(['reporter', 'employee'])->withCount('photos')->orderByDesc('id');

        // Employees see only their own reports.
        if (! $this->canManage($request)) {
            $query->where('reported_by', $request->user()->id);
        }

        $reports  = $query->get();
        $canManage = $this->canManage($request);

        return view('backend.incident_reports.index', compact('reports', 'canManage'));
    }

    public function create(Request $request)
    {
        return view('backend.incident_reports.create', [
            'canManage' => $this->canManage($request),
            'employees' => $this->employees(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateReport($request);

        $isManager = $this->canManage($request);

        $report = IncidentReport::create([
            'reported_by'      => $request->user()->id,
            // Managers can log on behalf of an employee; otherwise it's the reporter.
            'employee_id'      => $isManager ? ($validated['employee_id'] ?? null) : $request->user()->id,
            'reporter_name'    => $validated['reporter_name'],
            'incident_date'    => $validated['incident_date'],
            'incident_time'    => $validated['incident_time'] ?? null,
            'location'         => $validated['location'],
            'category'         => $validated['category'],
            'severity'         => $validated['severity'],
            'description'      => $validated['description'],
            'immediate_action' => $validated['immediate_action'] ?? null,
            'witnesses'        => $validated['witnesses'] ?? null,
            'status'           => 'open',
            'created_by'       => $request->user()->id,
        ]);

        // Reference number uses the DB id once known.
        $report->reference_no = 'IR-'.$report->created_at->format('Y').'-'.str_pad((string) $report->id, 4, '0', STR_PAD_LEFT);
        $report->save();

        $this->storePhotos($request, $report);

        return redirect()->route('admin.incident-reports.index')->with('message', 'Incident report submitted successfully.');
    }

    public function show(Request $request, IncidentReport $incident_report)
    {
        $this->authorizeView($request, $incident_report);

        return view('backend.incident_reports.show', [
            'report'    => $incident_report->load(['reporter', 'employee', 'reviewer', 'photos']),
            'canManage' => $this->canManage($request),
        ]);
    }

    public function edit(Request $request, IncidentReport $incident_report)
    {
        // Only managers reach here (route is gated by incident-reports.edit).
        return view('backend.incident_reports.edit', [
            'report'    => $incident_report->load('photos'),
            'employees' => $this->employees(),
        ]);
    }

    public function update(Request $request, IncidentReport $incident_report)
    {
        $validated = $this->validateReport($request, true);

        $incident_report->fill([
            'employee_id'      => $validated['employee_id'] ?? null,
            'reporter_name'    => $validated['reporter_name'],
            'incident_date'    => $validated['incident_date'],
            'incident_time'    => $validated['incident_time'] ?? null,
            'location'         => $validated['location'],
            'category'         => $validated['category'],
            'severity'         => $validated['severity'],
            'description'      => $validated['description'],
            'immediate_action' => $validated['immediate_action'] ?? null,
            'witnesses'        => $validated['witnesses'] ?? null,
            'status'           => $validated['status'],
            'review_notes'     => $validated['review_notes'] ?? null,
        ]);

        // Stamp the reviewer whenever an admin touches the review fields.
        $incident_report->reviewed_by = $request->user()->id;
        $incident_report->reviewed_at = now();
        $incident_report->save();

        $this->storePhotos($request, $incident_report);

        return redirect()->route('admin.incident-reports.index')->with('message', 'Incident report updated successfully.');
    }

    public function destroy(IncidentReport $incident_report)
    {
        foreach ($incident_report->photos as $photo) {
            $this->deleteFile($photo->file_path);
        }
        $incident_report->delete();

        return redirect()->route('admin.incident-reports.index')->with('message', 'Incident report deleted successfully.');
    }

    /** Remove a single photo (managers only, checked on the route). */
    public function destroyPhoto(\App\Models\IncidentReportPhoto $photo)
    {
        $this->deleteFile($photo->file_path);
        $reportId = $photo->incident_report_id;
        $photo->delete();

        return redirect()->route('admin.incident-reports.edit', $reportId)->with('message', 'Photo removed.');
    }

    // ---------------------------------------------------------------

    private function authorizeView(Request $request, IncidentReport $report): void
    {
        if (! $this->canManage($request) && $report->reported_by !== $request->user()->id) {
            abort(403, 'You can only view your own incident reports.');
        }
    }

    private function validateReport(Request $request, bool $isUpdate = false): array
    {
        $rules = [
            'reporter_name'    => 'required|string|max:255',
            'employee_id'      => 'nullable|exists:users,id',
            'incident_date'    => 'required|date',
            'incident_time'    => 'nullable',
            'location'         => 'required|string|max:255',
            'category'         => ['required', Rule::in(array_keys(IncidentReport::CATEGORIES))],
            'severity'         => ['required', Rule::in(array_keys(IncidentReport::SEVERITIES))],
            'description'      => 'required|string',
            'immediate_action' => 'nullable|string',
            'witnesses'        => 'nullable|string|max:255',
            'photos'           => 'nullable|array',
            'photos.*'         => 'image|mimes:jpg,jpeg,png,gif,webp|max:'.config('uploads.image_max_kb'),
        ];

        // Status + review notes only when a manager edits.
        if ($isUpdate) {
            $rules['status']       = ['required', Rule::in(array_keys(IncidentReport::STATUSES))];
            $rules['review_notes'] = 'nullable|string';
        }

        return $request->validate($rules, [
            'photos.*.image' => 'Each attachment must be an image (JPG, PNG, GIF or WEBP).',
            'photos.*.max'   => 'Each image may not be larger than '.round(config('uploads.image_max_kb') / 1024).' MB.',
        ]);
    }

    private function storePhotos(Request $request, IncidentReport $report): void
    {
        if (! $request->hasFile('photos')) {
            return;
        }

        $dir = public_path('uploads/incident-reports');
        if (! is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        foreach ($request->file('photos') as $file) {
            if (! $file->isValid()) {
                continue;
            }
            $base = preg_replace('/[^A-Za-z0-9_\-]/', '', preg_replace('/\s+/', '_', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)));
            $base = $base !== '' ? $base : 'photo';
            $originalName = $file->getClientOriginalName();
            $size         = $file->getSize();
            $mime         = $file->getClientMimeType();
            $filename     = $base.'_'.time().'_'.mt_rand(100, 999).'.'.$file->getClientOriginalExtension();
            $file->move($dir, $filename);

            $report->photos()->create([
                'file_path'     => 'uploads/incident-reports/'.$filename,
                'original_name' => $originalName,
                'file_size'     => $size,
                'mime_type'     => $mime,
            ]);
        }
    }

    private function deleteFile(?string $path): void
    {
        if ($path && file_exists(public_path($path))) {
            @unlink(public_path($path));
        }
    }
}
