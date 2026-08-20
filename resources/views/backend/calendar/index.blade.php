<!doctype html>
<html lang="en">
<head>
    @include('components.backend.head')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/plugins/fullcalendar/css/main.min.css') }}">
</head>
<body>
@include('components.backend.header')
@include('components.backend.sidebar')

<div class="page-body">
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6"><h3>Team Calendar</h3></div>
                <div class="col-6 text-end">
                    @if(auth()->user()->hasPermission('calendar.create'))
                        <a href="{{ route('admin.calendar.create') }}" class="btn btn-primary">+ New Event</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        {{-- Legend + month/year jump --}}
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                            <div class="cal-legend mb-0">
                                @foreach(\App\Models\CalendarEvent::CATEGORIES as $key => $c)
                                    <span class="cal-legend-item">
                                        <span class="cal-legend-dot" style="background: {{ $c['color'] }}"></span>{{ $c['label'] }}
                                    </span>
                                @endforeach
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <label for="calMonthPicker" class="small text-muted mb-0">Jump to:</label>
                                <input type="month" id="calMonthPicker" class="form-control form-control-sm cal-month-picker">
                            </div>
                        </div>
                        <div id="teamCalendar"></div>
                        @if(auth()->user()->hasPermission('calendar.create') || auth()->user()->hasPermission('calendar.edit'))
                            <small class="text-muted d-block mt-3"><i class="fa fa-info-circle me-1"></i>Click a day to add an event, or click an event to edit it.</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- List of events --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-3">All Events</h6>
                        <div class="table-responsive">
                            <table id="basic-1" class="display table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Event</th>
                                        <th>Category</th>
                                        <th>When</th>
                                        <th>Status</th>
                                        <th class="text-end" style="min-width:150px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($events as $e)
                                        <tr>
                                            <td>{{ $e->title }}</td>
                                            <td><span class="badge" style="background: {{ $e->color }}">{{ $e->category_label }}</span></td>
                                            <td>
                                                {{ optional($e->start_date)->format('d M Y') }}
                                                @if($e->end_date && $e->end_date->ne($e->start_date)) &ndash; {{ $e->end_date->format('d M Y') }} @endif
                                                @unless($e->all_day)
                                                    <div class="small text-muted">{{ $e->start_time ? \Illuminate\Support\Str::of($e->start_time)->beforeLast(':') : '' }} @if($e->end_time) - {{ \Illuminate\Support\Str::of($e->end_time)->beforeLast(':') }} @endif</div>
                                                @endunless
                                            </td>
                                            <td>
                                                @if($e->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Hidden</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="d-flex gap-1 justify-content-end">
                                                    @if(auth()->user()->hasPermission('calendar.edit'))
                                                        <a href="{{ route('admin.calendar.edit', $e) }}" class="btn btn-sm btn-primary">Edit</a>
                                                    @endif
                                                    @if(auth()->user()->hasPermission('calendar.delete'))
                                                        <form action="{{ route('admin.calendar.destroy', $e) }}" method="POST" class="m-0" onsubmit="return confirm('Delete this event?')">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center text-muted py-4">No events yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('components.backend.footer')
</div>
</div>

@include('components.backend.main-js')
<script src="{{ asset('admin/assets/plugins/fullcalendar/js/main.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var el = document.getElementById('teamCalendar');
        if (!el || !window.FullCalendar) return;

        var canCreate = @json(auth()->user()->hasPermission('calendar.create'));
        var canEdit   = @json(auth()->user()->hasPermission('calendar.edit'));

        var picker = document.getElementById('calMonthPicker');

        var calendar = new FullCalendar.Calendar(el, {
            initialView: 'dayGridMonth',
            headerToolbar: { left: 'prevYear prev next nextYear today', center: 'title', right: 'dayGridMonth listMonth' },
            buttonText: { today: 'Today', month: 'Month', list: 'List' },
            buttonHints: { prevYear: 'Previous year', nextYear: 'Next year' },
            height: 'auto',
            fixedWeekCount: false,
            dayMaxEvents: 2,
            navLinks: true,
            eventDisplay: 'block',
            displayEventTime: true,
            eventTimeFormat: { hour: 'numeric', minute: '2-digit', meridiem: 'short' },
            events: '{{ route('admin.calendar.events') }}',
            // Keep the month picker in sync with whatever month is being viewed.
            datesSet: function () {
                if (picker) {
                    var d = calendar.getDate();
                    picker.value = d.getFullYear() + '-' + ('0' + (d.getMonth() + 1)).slice(-2);
                }
            },
            eventClick: function (info) {
                if (canEdit) {
                    info.jsEvent.preventDefault();
                    window.location = '{{ url('calendar') }}/' + info.event.id + '/edit';
                }
            },
            dateClick: function (info) {
                if (canCreate) {
                    window.location = '{{ route('admin.calendar.create') }}?date=' + info.dateStr;
                }
            }
        });
        calendar.render();

        // Jump to any month/year picked.
        if (picker) {
            picker.addEventListener('change', function () {
                if (this.value) { calendar.gotoDate(this.value + '-01'); }
            });
        }
    });
</script>
</body>
</html>
