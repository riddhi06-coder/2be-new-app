<!doctype html>
<html lang="en">

    <head>
        @include('components.frontend.head')
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/plugins/fullcalendar/css/main.min.css') }}">
    </head>

    <body>

        @include('components.frontend.employee_header')

            <section class="pumping-log">
                <div class="container">
                    <div class="col-md-12">
                    <div class="pumping-log__content">
                        <h1 class="pumping-log__title">
                        <span class="pumping-log__brand">Community</span>
                        Calendar
                        </h1>
                        <p class="pumping-log__description">
                        Company holidays, meetings, trainings and events — all in one place.
                        </p>
                    </div>
                    </div>
                </div>
                <svg class="shape-one" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none">
                    <path class="elementor-shape-fill" d="M421.9,6.5c22.6-2.5,51.5,0.4,75.5,5.3c23.6,4.9,70.9,23.5,100.5,35.7c75.8,32.2,133.7,44.5,192.6,49.7
                    c23.6,2.1,48.7,3.5,103.4-2.5c54.7-6,106.2-25.6,106.2-25.6V0H0v30.3c0,0,72,32.6,158.4,30.5c39.2-0.7,92.8-6.7,134-22.4
                    c21.2-8.1,52.2-18.2,79.7-24.2C399.3,7.9,411.6,7.5,421.9,6.5z"></path>
                </svg>
            </section>

            <section class="cal-page">
            <div class="container">
                <div class="account-breadcrumb doclib-breadcrumb">
                    @auth
                        <a href="{{ route('frontend.employee_dashboard') }}">Dashboard</a>
                    @else
                        <a href="{{ route('frontend.employee_portal') }}">Home</a>
                    @endauth
                    <i class="fa fa-angle-right"></i>
                    <span class="current">Community Calendar</span>
                </div>

                <div class="row g-4">
                    <!-- Calendar -->
                    <div class="col-lg-8">
                        <div class="cal-card">
                            <div class="cal-legend-bar">
                                @foreach($categories as $key => $c)
                                    <span class="cal-legend-chip">
                                        <span class="cal-legend-dot" style="background: {{ $c['color'] }}"></span>{{ $c['label'] }}
                                    </span>
                                @endforeach
                            </div>
                            <div id="communityCalendar"></div>
                        </div>
                    </div>

                    <!-- Upcoming events -->
                    <div class="col-lg-4">
                        <div class="cal-card cal-upcoming">
                            <h3 class="cal-upcoming__title"><i class="fa fa-calendar-check-o"></i> Upcoming Events</h3>

                            @forelse($upcoming as $event)
                                <div class="cal-event">
                                    <div class="cal-event__date" style="--dot: {{ $event->color }}">
                                        <span class="cal-event__day">{{ optional($event->start_date)->format('d') }}</span>
                                        <span class="cal-event__mon">{{ optional($event->start_date)->format('M') }}</span>
                                    </div>
                                    <div class="cal-event__info">
                                        <span class="cal-event__title">{{ $event->title }}</span>
                                        <span class="cal-event__cat" style="color: {{ $event->color }}">
                                            <span class="cal-legend-dot" style="background: {{ $event->color }}"></span>{{ $event->category_label }}
                                        </span>
                                        <span class="cal-event__meta">
                                            @if($event->all_day)
                                                <i class="fa fa-clock-o"></i> All day
                                            @elseif($event->start_time)
                                                <i class="fa fa-clock-o"></i> {{ \Illuminate\Support\Carbon::parse($event->start_time)->format('g:i A') }}
                                            @endif
                                            @if($event->location)
                                                <span class="cal-event__loc"><i class="fa fa-map-marker"></i> {{ $event->location }}</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="emp-empty">
                                    <i class="fa fa-calendar-o"></i>
                                    <p>No upcoming events scheduled.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            </section>

            <!-- Event details modal -->
            <div class="modal fade" id="calEventModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content cal-modal">
                        <div class="modal-header">
                            <span class="cal-modal__cat" id="calModalCat"></span>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <h4 class="cal-modal__title" id="calModalTitle"></h4>
                            <div class="cal-modal__row"><i class="fa fa-calendar"></i> <span id="calModalDate"></span></div>
                            <div class="cal-modal__row"><i class="fa fa-clock-o"></i> <span id="calModalTime"></span></div>
                            <div class="cal-modal__row" id="calModalLocWrap"><i class="fa fa-map-marker"></i> <span id="calModalLoc"></span></div>
                            <p class="cal-modal__desc" id="calModalDesc"></p>
                        </div>
                    </div>
                </div>
            </div>

        @include('components.frontend.footer')

        @include('components.frontend.main-js')
        <script src="{{ asset('admin/assets/plugins/fullcalendar/js/main.min.js') }}"></script>
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            var el = document.getElementById('communityCalendar');
            if (!el || !window.FullCalendar) return;

            var calendar = new FullCalendar.Calendar(el, {
                initialView: 'dayGridMonth',
                headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,listMonth' },
                buttonText: { today: 'Today', month: 'Month', list: 'List' },
                height: 'auto',
                fixedWeekCount: false,
                dayMaxEvents: 3,
                eventDisplay: 'block',
                eventTimeFormat: { hour: 'numeric', minute: '2-digit', meridiem: 'short' },
                events: '{{ route('frontend.employee_calendar_events') }}',
                eventClick: function (info) {
                    info.jsEvent.preventDefault();
                    var p = info.event.extendedProps;

                    document.getElementById('calModalTitle').textContent = info.event.title;
                    document.getElementById('calModalDate').textContent  = p.dateLabel || '';
                    document.getElementById('calModalTime').textContent  = p.timeLabel || '';

                    var cat = document.getElementById('calModalCat');
                    cat.textContent = p.category || 'Event';
                    cat.style.background = (p.color || '#0004fe') + '22';
                    cat.style.color = p.color || '#0004fe';

                    var locWrap = document.getElementById('calModalLocWrap');
                    if (p.location) {
                        document.getElementById('calModalLoc').textContent = p.location;
                        locWrap.style.display = '';
                    } else {
                        locWrap.style.display = 'none';
                    }

                    var desc = document.getElementById('calModalDesc');
                    desc.textContent = p.description || '';
                    desc.style.display = p.description ? '' : 'none';

                    if (window.bootstrap && bootstrap.Modal) {
                        bootstrap.Modal.getOrCreateInstance(document.getElementById('calEventModal')).show();
                    }
                }
            });
            calendar.render();
        });
        </script>

    </body>

</html>
