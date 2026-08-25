<!doctype html>
<html lang="en">

    <head>
        @include('components.frontend.head')
    </head>

    <body>

        @include('components.frontend.employee_header')

            <section class="ir-thankyou">
                <div class="container">
                    <div class="ir-ty-card">
                        <div class="ir-ty-check">
                            <i class="fa fa-check"></i>
                        </div>

                        <h1 class="ir-ty-title">Thank You!</h1>
                        <p class="ir-ty-text">
                            Your incident report has been submitted successfully. Our safety team will review it shortly.
                            All reports are confidential and help us keep everyone safe.
                        </p>

                        <div class="ir-ty-ref">
                            <span class="ir-ty-ref__label">Reference Number</span>
                            <span class="ir-ty-ref__value">{{ $reference }}</span>
                        </div>

                        <p class="ir-ty-note">
                            <i class="fa fa-info-circle"></i>
                            Please keep this reference number for your records. You can track the status of your report from your dashboard.
                        </p>

                        <div class="ir-ty-actions">
                            <a href="{{ route('frontend.employee_incident_report') }}" class="ir-ty-btn ir-ty-btn--outline">
                                <i class="fa fa-plus"></i> Submit Another Report
                            </a>
                            <a href="{{ route('frontend.employee_dashboard') }}#my-reports" class="ir-ty-btn ir-ty-btn--primary">
                                <i class="fa fa-list-alt"></i> View My Reports
                            </a>
                        </div>

                        <a href="{{ route('frontend.employee_dashboard') }}" class="ir-ty-home">
                            <i class="fa fa-long-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
            </section>

        @include('components.frontend.footer')

        @include('components.frontend.main-js')

    </body>

</html>
