<!doctype html>
<html lang="en">

    <head>
        @include('components.frontend.head')
    </head>

    <body>

        @include('components.frontend.employee_header')

        @php
            $statusMap = [
                'open'         => ['label' => 'Open',         'class' => 'is-open'],
                'under-review' => ['label' => 'Under Review', 'class' => 'is-review'],
                'closed'       => ['label' => 'Closed',       'class' => 'is-closed'],
            ];
        @endphp

            <!-- Breadcrumb -->
            <div class="account-breadcrumb">
                <div class="container">
                    <a href="{{ route('frontend.employee_portal') }}">Home</a>
                    <i class="fa fa-angle-right"></i>
                    <span class="current">My Dashboard</span>
                </div>
            </div>

            <section class="account-section">
            <div class="container">
                <div class="row g-4">

                    <!-- Sidebar card -->
                    <div class="col-lg-4 col-xl-3">
                        <div class="account-sidebar">
                            <div class="account-sidebar__profile">
                                <div class="account-avatar"><i class="fa fa-user"></i></div>
                                <h4>{{ $employee->name }}</h4>
                                <p>{{ $employee->email }}</p>
                            </div>

                            {{-- Account-focused nav only; site-wide sections live in the top header.
                                 Dashboard / My Incident Reports switch the panel in-place (tabs). --}}
                            <nav class="account-menu">
                                <a href="#tab-dashboard" data-emp-tab="dashboard"
                                   class="account-menu__link active">
                                    <i class="fa fa-th-large"></i> <span>Dashboard</span>
                                </a>
                                <a href="#tab-reports" data-emp-tab="reports"
                                   class="account-menu__link">
                                    <i class="fa fa-exclamation-triangle"></i> <span>My Incident Reports</span>
                                </a>
                                <a href="{{ route('frontend.employee_documents') }}" class="account-menu__link">
                                    <i class="fa fa-folder"></i> <span>Document Library</span>
                                </a>
                                <a href="#" class="account-menu__link">
                                    <i class="fa fa-user"></i> <span>My Profile</span>
                                </a>
                                <a href="{{ route('frontend.employee_logout') }}" class="account-menu__link account-menu__link--logout">
                                    <i class="fa fa-sign-out"></i> <span>Logout</span>
                                </a>
                            </nav>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="col-lg-8 col-xl-9">

                        <!-- ============ Tab: Dashboard ============ -->
                        <div class="emp-tab-pane" id="tab-dashboard">

                        <!-- Welcome banner -->
                        <div class="emp-welcome">
                            <div>
                                <h1>Welcome back, {{ $employee->name }} 👋</h1>
                                <p>Here's your personal workspace — your incident reports and the latest company updates.</p>
                            </div>
                            <a href="{{ route('frontend.employee_incident_report') }}" class="emp-welcome__btn">
                                <i class="fa fa-plus"></i> Report an Incident
                            </a>
                        </div>

                        <!-- Stats -->
                        <div class="row g-3 emp-stats">
                            <div class="col-6 col-lg-3">
                                <div class="emp-stat emp-stat--total">
                                    <div class="emp-stat__icon"><i class="fa fa-file-text-o"></i></div>
                                    <div class="emp-stat__num">{{ $reportStats['total'] }}</div>
                                    <div class="emp-stat__label">My Reports</div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3">
                                <div class="emp-stat emp-stat--open">
                                    <div class="emp-stat__icon"><i class="fa fa-folder-open-o"></i></div>
                                    <div class="emp-stat__num">{{ $reportStats['open'] }}</div>
                                    <div class="emp-stat__label">Open</div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3">
                                <div class="emp-stat emp-stat--review">
                                    <div class="emp-stat__icon"><i class="fa fa-clock-o"></i></div>
                                    <div class="emp-stat__num">{{ $reportStats['review'] }}</div>
                                    <div class="emp-stat__label">Under Review</div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3">
                                <div class="emp-stat emp-stat--closed">
                                    <div class="emp-stat__icon"><i class="fa fa-check-circle-o"></i></div>
                                    <div class="emp-stat__num">{{ $reportStats['closed'] }}</div>
                                    <div class="emp-stat__label">Closed</div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-4 emp-panels">
                            <!-- My recent reports -->
                            <div class="col-xl-7">
                                <div class="emp-panel">
                                    <div class="emp-panel__head">
                                        <h3><i class="fa fa-list-alt"></i> My Recent Incident Reports</h3>
                                        <a href="#tab-reports" data-emp-tab="reports" class="emp-panel__action">View all</a>
                                    </div>
                                    <div class="emp-panel__body">
                                        @forelse($myReports as $report)
                                            @php $st = $statusMap[$report->status] ?? ['label' => ucfirst($report->status), 'class' => '']; @endphp
                                            <div class="emp-report">
                                                <div class="emp-report__main">
                                                    <span class="emp-report__ref">{{ $report->reference_no }}</span>
                                                    <span class="emp-report__cat">{{ $report->category_label }}</span>
                                                </div>
                                                <div class="emp-report__meta">
                                                    <span class="emp-report__date">
                                                        <i class="fa fa-calendar"></i>
                                                        {{ optional($report->incident_date)->format('M d, Y') }}
                                                    </span>
                                                    <span class="emp-status {{ $st['class'] }}">{{ $st['label'] }}</span>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="emp-empty">
                                                <i class="fa fa-inbox"></i>
                                                <p>You haven't submitted any incident reports yet.</p>
                                                <a href="{{ route('frontend.employee_incident_report') }}" class="emp-empty__btn">Report an Incident</a>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                            <!-- Latest announcements -->
                            <div class="col-xl-5">
                                <div class="emp-panel">
                                    <div class="emp-panel__head">
                                        <h3><i class="fa fa-bullhorn"></i> Latest Announcements</h3>
                                        <a href="{{ route('frontend.employee_announcements') }}" class="emp-panel__action">View All</a>
                                    </div>
                                    <div class="emp-panel__body">
                                        @forelse($announcements as $announcement)
                                            @php $date = optional($announcement->published_at) ?: $announcement->created_at; @endphp
                                            <a href="{{ route('frontend.employee_announcement', $announcement->slug) }}" class="emp-ann">
                                                <span class="emp-ann__dot"></span>
                                                <span class="emp-ann__body">
                                                    <span class="emp-ann__title">{{ $announcement->title }}</span>
                                                    <span class="emp-ann__date">{{ $date->format('M d, Y') }}</span>
                                                </span>
                                            </a>
                                        @empty
                                            <div class="emp-empty">
                                                <i class="fa fa-bullhorn"></i>
                                                <p>No announcements right now.</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div><!-- /#tab-dashboard -->

                        <!-- ============ Tab: My Incident Reports ============ -->
                        <div class="emp-tab-pane d-none" id="tab-reports">
                            <div class="emp-panel">
                                <div class="emp-panel__head">
                                    <h3><i class="fa fa-list-alt"></i> My Incident Reports</h3>
                                    <a href="{{ route('frontend.employee_incident_report') }}" class="emp-report-new-btn"><i class="fa fa-plus"></i> Report New</a>
                                </div>
                                <div class="emp-panel__body">
                                    @if($myReportsAll->count())
                                        <div class="emp-filter">
                                            <button type="button" class="emp-filter__chip active" data-filter="all">All ({{ $reportStats['total'] }})</button>
                                            <button type="button" class="emp-filter__chip" data-filter="open">Open ({{ $reportStats['open'] }})</button>
                                            <button type="button" class="emp-filter__chip" data-filter="under-review">Under Review ({{ $reportStats['review'] }})</button>
                                            <button type="button" class="emp-filter__chip" data-filter="closed">Closed ({{ $reportStats['closed'] }})</button>
                                        </div>

                                        @php $sevMap = ['minor' => 'sev-minor', 'moderate' => 'sev-moderate', 'serious' => 'sev-serious']; @endphp
                                        @foreach($myReportsAll as $report)
                                            @php $st = $statusMap[$report->status] ?? ['label' => ucfirst($report->status), 'class' => '']; @endphp
                                            <div class="emp-rcard" data-status="{{ $report->status }}">
                                                <button type="button" class="emp-rcard__header js-rcard-toggle" aria-expanded="false">
                                                    <span class="emp-rcard__id">
                                                        <span class="emp-rcard__ref">{{ $report->reference_no }}</span>
                                                        <span class="emp-rcard__sub">{{ $report->category_label }} &middot; {{ optional($report->incident_date)->format('M d, Y') }}</span>
                                                    </span>
                                                    <span class="emp-rcard__right">
                                                        <span class="emp-status {{ $st['class'] }}">{{ $st['label'] }}</span>
                                                        <i class="fa fa-chevron-down emp-rcard__chevron"></i>
                                                    </span>
                                                </button>

                                                <div class="emp-rcard__body">
                                                    <div class="emp-rcard__inner">
                                                        <div class="emp-rcard__grid">
                                                            <div class="emp-rcard__field">
                                                                <span class="emp-rcard__label">Category</span>
                                                                <span class="emp-rcard__value">{{ $report->category_label }}</span>
                                                            </div>
                                                            <div class="emp-rcard__field">
                                                                <span class="emp-rcard__label">Severity</span>
                                                                <span class="emp-sev {{ $sevMap[$report->severity] ?? '' }}">{{ $report->severity_label }}</span>
                                                            </div>
                                                            <div class="emp-rcard__field">
                                                                <span class="emp-rcard__label">Date &amp; Time</span>
                                                                <span class="emp-rcard__value">{{ optional($report->incident_date)->format('M d, Y') }}{{ $report->incident_time ? ' · '.\Illuminate\Support\Str::of($report->incident_time)->substr(0,5) : '' }}</span>
                                                            </div>
                                                            <div class="emp-rcard__field">
                                                                <span class="emp-rcard__label">Location</span>
                                                                <span class="emp-rcard__value">{{ $report->location }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="emp-rcard__block">
                                                            <span class="emp-rcard__label">Description</span>
                                                            <p>{{ $report->description }}</p>
                                                        </div>

                                                        @if($report->immediate_action)
                                                            <div class="emp-rcard__block">
                                                                <span class="emp-rcard__label">Immediate Action Taken</span>
                                                                <p>{{ $report->immediate_action }}</p>
                                                            </div>
                                                        @endif

                                                        @if($report->witnesses)
                                                            <div class="emp-rcard__block">
                                                                <span class="emp-rcard__label">Witnesses</span>
                                                                <p>{{ $report->witnesses }}</p>
                                                            </div>
                                                        @endif

                                                        @if($report->photos->count())
                                                            <div class="emp-rcard__block">
                                                                <span class="emp-rcard__label">Photos</span>
                                                                <div class="emp-rcard__photos">
                                                                    @foreach($report->photos as $photo)
                                                                        <a href="{{ asset($photo->file_path) }}" target="_blank"><img src="{{ asset($photo->file_path) }}" alt="Incident photo"></a>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @if($report->review_notes)
                                                            <div class="emp-rcard__review">
                                                                <strong><i class="fa fa-comment-o"></i> Reviewer Notes</strong>
                                                                <p>{{ strip_tags($report->review_notes) }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        <div class="emp-report-empty-filter d-none">
                                            <i class="fa fa-filter"></i>
                                            <p>No reports match this filter.</p>
                                        </div>
                                    @else
                                        <div class="emp-empty">
                                            <i class="fa fa-inbox"></i>
                                            <p>You haven't submitted any incident reports yet.</p>
                                            <a href="{{ route('frontend.employee_incident_report') }}" class="emp-empty__btn">Report an Incident</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div><!-- /#tab-reports -->

                    </div>
                </div>
            </div>
            </section>

        @include('components.frontend.footer')

        @include('components.frontend.main-js')

        <script>
        (function () {
            var panes     = document.querySelectorAll('.emp-tab-pane');
            var tabLinks  = document.querySelectorAll('[data-emp-tab]');
            var menuLinks = document.querySelectorAll('.account-menu__link[data-emp-tab]');

            function activate(tab) {
                panes.forEach(function (p) { p.classList.toggle('d-none', p.id !== 'tab-' + tab); });
                menuLinks.forEach(function (l) { l.classList.toggle('active', l.getAttribute('data-emp-tab') === tab); });
            }

            tabLinks.forEach(function (link) {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    var tab = this.getAttribute('data-emp-tab');
                    activate(tab);
                    if (history.replaceState) {
                        history.replaceState(null, '', '#' + (tab === 'reports' ? 'my-reports' : 'dashboard'));
                    }
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            });

            // Deep-link (e.g. arriving at .../employee-dashboard#my-reports)
            if (location.hash === '#my-reports') { activate('reports'); }

            // Filter chips on the reports tab
            var chips   = document.querySelectorAll('.emp-filter__chip');
            var cards   = document.querySelectorAll('.emp-rcard');
            var noMatch = document.querySelector('.emp-report-empty-filter');

            chips.forEach(function (chip) {
                chip.addEventListener('click', function () {
                    chips.forEach(function (c) { c.classList.remove('active'); });
                    this.classList.add('active');
                    var f = this.getAttribute('data-filter'), shown = 0;
                    cards.forEach(function (card) {
                        var match = (f === 'all' || card.getAttribute('data-status') === f);
                        card.classList.toggle('d-none', !match);
                        if (match) shown++;
                    });
                    if (noMatch) noMatch.classList.toggle('d-none', shown !== 0);
                });
            });

            // Accordion: expand a report to reveal its full details
            document.querySelectorAll('.js-rcard-toggle').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var card = this.closest('.emp-rcard');
                    var isOpen = card.classList.toggle('is-expanded');
                    this.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                });
            });
        })();
        </script>

    </body>

</html>
