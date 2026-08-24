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
                        <span class="pumping-log__brand">Announcement</span>
                        </h1>

                        <p class="pumping-log__description">
                        <i class="fa fa-calendar"></i>
                        {{ optional($announcement->published_at)->format('F d, Y') ?? $announcement->created_at->format('F d, Y') }}
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
                    <div class="announcement-detail">
                        <a href="{{ route('frontend.employee_announcements') }}" class="announcement-back">
                            <i class="fa fa-long-arrow-left"></i> Back to all announcements
                        </a>

                        <h2 class="announcement-detail__title">{{ $announcement->title }}</h2>

                        @if($announcement->image_path)
                            <div class="announcement-detail__image">
                                <img src="{{ asset($announcement->image_path) }}" alt="{{ $announcement->title }}">
                            </div>
                        @endif

                        <div class="announcement-detail__body">
                            {!! $announcement->body !!}
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
