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
                        <h1 class="pumping-log__title">
                        <span class="pumping-log__brand">Company</span>
                        Announcements
                        </h1>

                        <p class="pumping-log__description">
                        Stay informed with the latest company news and important updates.
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

            <section class="announcement-boxes-wrap">
            <div class="container">
                <div class="row">
                <div class="col-md-12">
                    <div class="announcement-box">
                    <div class="announcement-content" style="width:100%;">
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

                                <p>{{ \Illuminate\Support\Str::limit(strip_tags($announcement->body), 220) }}</p>
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

                        @if($announcements->hasPages())
                            <div class="announcement-pagination">
                                {{ $announcements->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
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
