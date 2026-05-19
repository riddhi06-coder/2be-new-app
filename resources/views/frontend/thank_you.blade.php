<!doctype html>
<html lang="en">

<head>
    @include('components.frontend.head')
</head>

<body class="d-flex flex-column min-vh-100">
    
    @include('components.frontend.header')

    <main class="flex-fill">
        <section class="success-wrap">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6 col-sm-12">
                        <div class="success-card">
                            <div class="success-icon">
                                <img src="{{ asset('frontend/assets/images/checklist.png') }}" alt="success">
                            </div>
                            <h1>Thank You!</h1>
                            <p>Your waste disposal entry has been submitted.</p>
                            <a href="{{ route('frontend.index') }}" class="btn btn-primary">Add New Entry</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @include('components.frontend.footer')
    @include('components.frontend.main-js')

</body>


</html>
