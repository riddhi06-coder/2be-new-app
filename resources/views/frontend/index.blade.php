<!doctype html>
<html lang="en">
    
<head>
    @include('components.frontend.head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <section class="header-wrap">
        <div class="container-fluid text-center">
        <div class="header-img-box">
            <img src="{{ asset('frontend/assets/images/logo.webp') }}" class="img-responsive">
            <h1>2BE Pumping Log</h1>
        </div>
        </div>
    </section>

    <section class="log-btn-wrap log-btn-home-wrap">
        <div class="container">

            <div class="row">
                <div class="col-md-12">
                <div class="log-box">
                    <div class="first-log-box">
                    <h2>Wastewater Pumping and Hauling Source Report</h2>
                    <div class="single-log-box">
                        <img src="{{ asset('frontend/assets/images/report.svg')}}">
                        <div class="single-log-text">
                        <h3>Wastewater Pumping & Hauling</h3>
                        <a href="{{ route('frontend.log_waste_disposal') }}" class="btn">Log a Waste Disposal <i class="fa fa-long-arrow-right"></i></a>
                        </div>
                    </div>
                    </div>

                    <div class="home-divider"></div>
                    <div class="second-single-log-box">
                    <div class="second-single-log-box-heading">
                        <h2>Inspection Forms</h2>
                        <p>Choose the appropriate inspection form based on the system type.</p>
                    </div>
                    <div class="inspection-form-wrap">
                        <div class="single-inspection-form">
                        <img src="{{ asset('frontend/assets/images/icon1.svg') }}">
                        <div class="inspection-text">
                            <h3>Cesspool Systems</h3>
                            <a href="{{ route('frontend.cesspool_systems') }}" class="btn">Open Form <i class="fa fa-long-arrow-right"></i></a>
                        </div>
                        </div>
                        <div class="single-inspection-form">
                        <img src="{{ asset('frontend/assets/images/icon2.svg') }}">
                        <div class="inspection-text">
                            <h3>Septic Systems</h3>
                            <a href="{{ route('frontend.septic_systems') }}" class="btn">Open Form <i class="fa fa-long-arrow-right"></i></a>
                        </div>
                        </div>
                    </div>
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