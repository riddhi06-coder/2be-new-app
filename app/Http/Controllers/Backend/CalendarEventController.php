<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\CalendarEvent;
use Illuminate\Http\Request;
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

            return [
                'id'      => $e->id,
                'title'   => $e->title,
                'start'   => $start,
                'end'     => $end,
                'allDay'  => $allDay,
                'color'   => $e->color,
                'extendedProps' => [
                    'category' => $e->category_label,
                    'location' => $e->location,
                ],
            ];
        });

        return response()->json($data);
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

        CalendarEvent::create($validated + [
            'all_day'    => $request->boolean('all_day'),
            'is_active'  => $request->boolean('is_active'),
            'created_by' => $request->user()->id,
        ]);

        return redirect()->route('admin.calendar.index')->with('message', 'Event added successfully.');
    }

    public function edit(CalendarEvent $calendar)
    {
        return view('backend.calendar.edit', ['event' => $calendar]);
    }

    public function update(Request $request, CalendarEvent $calendar)
    {
        $validated = $this->validateEvent($request);

        $calendar->update($validated + [
            'all_day'   => $request->boolean('all_day'),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.calendar.index')->with('message', 'Event updated successfully.');
    }

    public function destroy(CalendarEvent $calendar)
    {
        $calendar->delete();
        return redirect()->route('admin.calendar.index')->with('message', 'Event deleted successfully.');
    }

    private function validateEvent(Request $request): array
    {
        return $request->validate([
            'title'       => 'required|string|max:255',
            'category'    => ['required', Rule::in(array_keys(CalendarEvent::CATEGORIES))],
            'start_date'  => 'required|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'start_time'  => 'nullable',
            'end_time'    => 'nullable',
            'location'    => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);
    }
}
