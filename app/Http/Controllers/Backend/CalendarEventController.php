<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\CalendarEvent;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

class CalendarEventController extends Controller
{
    public function index()
    {
        // Upcoming + recent events for the list section under the calendar.
        $events = CalendarEvent::orderByDesc('start_date')->orderByDesc('id')->get();
        return view('backend.calendar.index', compact('events'));
    }

    /** JSON feed consumed by FullCalendar (loads whatever month is in view). */
    public function events(Request $request)
    {
        $data = CalendarEvent::where('is_active', true)->get()->map(function (CalendarEvent $e) {
            $allDay = $e->all_day;

            if ($allDay) {
                $start = $e->start_date->toDateString();
                // FullCalendar treats all-day "end" as exclusive, so add a day.
                $end = $e->end_date ? $e->end_date->copy()->addDay()->toDateString() : null;
            } else {
                $start = $e->start_date->toDateString().($e->start_time ? 'T'.$e->start_time : '');
                $endDate = $e->end_date ?: $e->start_date;
                $end = $e->end_time ? $endDate->toDateString().'T'.$e->end_time : null;
            }

            // Editorial "chip" styling: light-tinted background + colored text/accent.
            [$r, $g, $b] = $this->hexToRgb($e->color);

            return [
                'id'      => $e->id,
                'title'   => $e->title,
                'start'   => $start,
                'end'     => $end,
                'allDay'  => $allDay,
                'backgroundColor' => "rgba($r, $g, $b, 0.14)",
                'borderColor'     => $e->color,
                'textColor'       => $e->color,
                'extendedProps' => [
                    'category' => $e->category_label,
                    'location' => $e->location,
                ],
            ];
        });

        return response()->json($data);
    }

    /** Convert a #rrggbb hex colour to an [r, g, b] array. */
    private function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }
        return [hexdec(substr($hex, 0, 2)), hexdec(substr($hex, 2, 2)), hexdec(substr($hex, 4, 2))];
    }

    public function create(Request $request)
    {
        return view('backend.calendar.create', [
            'date' => $request->query('date'), // prefilled when a day is clicked on the calendar
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateEvent($request);
        unset($validated['attachment'], $validated['remove_attachment']);

        $event = CalendarEvent::create($validated + [
            'all_day'    => $request->boolean('all_day'),
            'is_active'  => $request->boolean('is_active'),
            'created_by' => $request->user()->id,
        ]);

        if ($request->hasFile('attachment')) {
            $event->attachment = $this->storeAttachment($request->file('attachment'));
            $event->save();
        }

        return redirect()->route('admin.community-calendar.index')->with('message', 'Event added successfully.');
    }

    public function edit(CalendarEvent $calendar)
    {
        return view('backend.calendar.edit', ['event' => $calendar]);
    }

    public function update(Request $request, CalendarEvent $calendar)
    {
        $validated = $this->validateEvent($request);
        unset($validated['attachment'], $validated['remove_attachment']);

        $calendar->update($validated + [
            'all_day'   => $request->boolean('all_day'),
            'is_active' => $request->boolean('is_active'),
        ]);

        // Remove the current flyer if requested (and no replacement uploaded).
        if ($request->boolean('remove_attachment') && ! $request->hasFile('attachment')) {
            $this->deleteAttachment($calendar->attachment);
            $calendar->attachment = null;
            $calendar->save();
        }

        // Replace with a newly uploaded flyer.
        if ($request->hasFile('attachment')) {
            $this->deleteAttachment($calendar->attachment);
            $calendar->attachment = $this->storeAttachment($request->file('attachment'));
            $calendar->save();
        }

        return redirect()->route('admin.community-calendar.index')->with('message', 'Event updated successfully.');
    }

    public function destroy(CalendarEvent $calendar)
    {
        $calendar->delete();
        return redirect()->route('admin.community-calendar.index')->with('message', 'Event deleted successfully.');
    }

    private function validateEvent(Request $request): array
    {
        return $request->validate([
            'title'             => 'required|string|max:255',
            'category'          => ['required', Rule::in(array_keys(CalendarEvent::CATEGORIES))],
            'start_date'        => 'required|date',
            'end_date'          => 'nullable|date|after_or_equal:start_date',
            'start_time'        => 'nullable',
            'end_time'          => 'nullable',
            'location'          => 'nullable|string|max:255',
            'description'       => 'nullable|string',
            'attachment'        => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,webp|max:'.config('uploads.document_max_kb'),
            'remove_attachment' => 'nullable|boolean',
        ], [
            'attachment.mimes' => 'The flyer must be a PDF, Word document, or image (JPG, PNG, WEBP).',
            'attachment.max'   => 'The flyer may not be larger than '.round(config('uploads.document_max_kb') / 1024).' MB.',
        ]);
    }

    /** Move an uploaded flyer/document into public/uploads/calendar with a safe, unique name. */
    private function storeAttachment(UploadedFile $file): string
    {
        $dir = public_path('uploads/calendar');
        if (! is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        $base = preg_replace('/[^A-Za-z0-9_\-]/', '', preg_replace('/\s+/', '_', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))) ?: 'flyer';
        $filename = $base.'_'.time().'_'.mt_rand(1000, 9999).'.'.$file->getClientOriginalExtension();
        $file->move($dir, $filename);

        return 'uploads/calendar/'.$filename;
    }

    private function deleteAttachment(?string $path): void
    {
        if ($path && file_exists(public_path($path))) {
            @unlink(public_path($path));
        }
    }
}
