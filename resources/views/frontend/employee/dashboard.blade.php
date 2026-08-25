<!doctype html>
<html lang="en">

    <head>
        @include('components.frontend.head')
    </head>

    <body>

        @include('components.frontend.employee_header')


            <section class="pumping-log">
                <div class="container">
                    <div class="col-md-12">
                    <div class="pumping-log__content">
                        <p class="pumping-log__welcome">Welcome to</p>

                        <h1 class="pumping-log__title">
                        <span class="pumping-log__brand">2BE</span>
                        Pumping Log
                        </h1>

                        <p class="pumping-log__description">
                        Your central hub for company policies, safety programs, and important resources.
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

            <section class="pumping-log-boxes-wrap">
                <div class="container">
                    <div class="col-md-12">
                    <div class="psdude-info-boxes">
                        <!-- Box 1 -->
                        <div class="psdude-info-box">
                        <div class="psdude-info-box__icon">
                            <img src="{{ asset('frontend/assets/images/folder.svg') }}" alt="Employee Handbook" />
                        </div>

                        <h3>Employee<br />Handbook</h3>
                        <span class="psdude-info-box__line"></span>

                        <p>Company policies,<br />procedures and guidelines</p>

                        <a href="{{ route('frontend.employee_document_category', ['slug' => 'employee-handbook', 'space' => 'public']) }}" class="btn psdude-info-box__btn">
                            View Handbook
                            <i class="fa fa-long-arrow-right"></i>
                        </a>
                        </div>

                        <!-- Box 2 -->
                        <div class="psdude-info-box">
                        <div class="psdude-info-box__icon">
                            <img src="{{ asset('frontend/assets/images/safety.svg') }}" alt="Safety Programs" />
                        </div>

                        <h3>Safety<br />Programs</h3>
                        <span class="psdude-info-box__line"></span>

                        <p>Safety policies, programs<br />and employee acknowledgements</p>

                        <a href="{{ route('frontend.employee_document_category', ['slug' => 'safety-programs', 'space' => 'public']) }}" class="btn psdude-info-box__btn">
                            View Programs
                            <i class="fa fa-long-arrow-right"></i>
                        </a>
                        </div>

                        <!-- Box 3 -->
                        <div class="psdude-info-box">
                        <div class="psdude-info-box__icon">
                            <img src="{{ asset('frontend/assets/images/doc.svg') }}" alt="Incident Report Form" />
                        </div>

                        <h3>Incident Report<br />Form</h3>
                        <span class="psdude-info-box__line"></span>

                        <p>Report incidents and<br />submit documentation</p>

                        <a href="{{ route('frontend.employee_incident_report') }}" class="btn psdude-info-box__btn psdude-info-box__btn--red">
                            Submit Report
                            <i class="fa fa-long-arrow-right"></i>
                        </a>
                        </div>

                        <!-- Box 4 -->
                        <div class="psdude-info-box">
                        <div class="psdude-info-box__icon">
                            <img src="{{ asset('frontend/assets/images/law.svg') }}" alt="Labor Poster Laws" />
                        </div>

                        <h3>Labor Poster<br />Laws</h3>
                        <span class="psdude-info-box__line"></span>

                        <p>Federal and state labor<br />law posters and notices</p>

                        <a href="{{ route('frontend.employee_documents') }}" class="btn psdude-info-box__btn">
                            View Posters
                            <i class="fa fa-long-arrow-right"></i>
                        </a>
                        </div>

                        <!-- Box 5 -->
                        <div class="psdude-info-box">
                        <div class="psdude-info-box__icon">
                            <img src="{{ asset('frontend/assets/images/time.svg') }}" alt="Current Month Community Calendar" />
                        </div>

                        <h3>Current Month<br />Community Calendar</h3>
                        <span class="psdude-info-box__line"></span>

                        <p>View team schedule<br />and important dates</p>

                        <a href="{{ route('frontend.employee_calendar') }}" class="btn psdude-info-box__btn">
                            View Calendar
                            <i class="fa fa-long-arrow-right"></i>
                        </a>
                        </div>
                    </div>
                    </div>
                </div>
            </section>

            <section class="announcement-boxes-wrap">
            <div class="container">
                <div class="row">
                <div class="col-md-12">
                    <div class="announcement-box">
                    <!-- Left: Announcements -->
                    <div class="announcement-content">
                        <!-- Header -->
                        <div class="announcement-header">
                        <div class="announcement-title">
                            <span class="header-icon">
                            <i class="fa fa-star"></i>
                            </span>

                            <div>
                            <h3>Announcements</h3>
                            <p>Stay informed with the latest company news and important updates.</p>
                            </div>
                        </div>

                        <a href="{{ route('frontend.employee_announcements') }}" class="view-announcements">
                            View All Announcements
                            <span class="arrow">
                            <i class="fa fa-long-arrow-right"></i>
                            </span>
                        </a>
                        </div>

                        <!-- Announcement Items (dynamic) -->
                        @forelse($announcements as $announcement)
                            @php
                                $isNew = $announcement->published_at && $announcement->published_at->gt(now()->subDays(7));
                            @endphp
                            <div class="announcement-item">
                            @if($isNew)
                                <div class="announcement-icon new-icon">NEW</div>
                            @else
                                <div class="announcement-icon policy-icon">
                                    <i class="fa fa-bullhorn"></i>
                                </div>
                            @endif

                            <div class="announcement-info">
                                <h4>
                                <a href="{{ route('frontend.employee_announcement', $announcement->slug) }}">{{ $announcement->title }}</a>
                                </h4>

                                <p>{{ \Illuminate\Support\Str::limit(strip_tags($announcement->body), 80) }}</p>
                            </div>

                            <div class="announcement-date">
                                <i class="fa fa-calendar"></i>
                                {{ optional($announcement->published_at)->format('M d, Y') ?? $announcement->created_at->format('M d, Y') }}
                            </div>
                            </div>
                        @empty
                            <div class="announcement-item">
                            <div class="announcement-info">
                                <p class="mb-0">No announcements at the moment. Please check back later.</p>
                            </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- Right: Safety Banner -->
                    <div class="safety-banner">
                        <div class="safety-overlay"></div>

                        <div class="safety-content">
                        <h3>
                            Safety is<br />
                            <span>Everyone's</span><br />
                            Responsibility
                        </h3>

                        <div class="red-line"></div>

                        <p>Working together for a<br />safer tomorrow.</p>
                        </div>

                        <div class="safety-badge">2BE</div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            </section>


        @include('components.frontend.footer')

        @include('components.frontend.main-js')

    </body>

</html>
