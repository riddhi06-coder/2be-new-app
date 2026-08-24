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
                        <span class="pumping-log__brand">Document</span>
                        Library
                        </h1>
                        <p class="pumping-log__description">
                        Browse company documents by category. Shared files and your personal documents are all here.
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

            <section class="doclib-wrap">
            <div class="container">
                <div class="account-breadcrumb doclib-breadcrumb">
                    @auth
                        <a href="{{ route('frontend.employee_dashboard') }}">Dashboard</a>
                    @else
                        <a href="{{ route('frontend.employee_portal') }}">Home</a>
                    @endauth
                    <i class="fa fa-angle-right"></i>
                    <span class="current">Document Library</span>
                </div>

                <!-- Public / Personal tabs -->
                <div class="doc-tabs">
                    <button type="button" class="doc-tab active" data-doc-tab="public">
                        <i class="fa fa-users"></i> Public
                    </button>
                    @auth
                        <button type="button" class="doc-tab" data-doc-tab="personal">
                            <i class="fa fa-user"></i> Personal
                        </button>
                    @endauth
                </div>

                <!-- Public pane -->
                <div class="doc-tab-pane" id="doc-public">
                    <p class="doc-space__sub">Files shared with the whole team.</p>
                    @if($publicCategories->isNotEmpty())
                        <div class="row g-4 justify-content-center">
                            @foreach($publicCategories as $category)
                                @include('frontend.employee.documents._folder', [
                                    'category' => $category,
                                    'count'    => $category->public_count,
                                    'space'    => 'public',
                                ])
                            @endforeach
                        </div>
                    @else
                        <div class="emp-empty">
                            <i class="fa fa-folder-open-o"></i>
                            <p>No public documents available.</p>
                        </div>
                    @endif
                </div>

                @auth
                <!-- Personal pane -->
                <div class="doc-tab-pane d-none" id="doc-personal">
                    <p class="doc-space__sub">Files assigned specifically to you.</p>
                    @if($personalCategories->isNotEmpty())
                        <div class="row g-4 justify-content-center">
                            @foreach($personalCategories as $category)
                                @include('frontend.employee.documents._folder', [
                                    'category' => $category,
                                    'count'    => $category->personal_count,
                                    'space'    => 'personal',
                                ])
                            @endforeach
                        </div>
                    @else
                        <div class="emp-empty">
                            <i class="fa fa-folder-open-o"></i>
                            <p>You don't have any personal documents yet.</p>
                        </div>
                    @endif
                </div>
                @endauth
            </div>
            </section>

            <script>
            (function () {
                var tabs  = document.querySelectorAll('.doc-tab');
                var panes = document.querySelectorAll('.doc-tab-pane');
                tabs.forEach(function (tab) {
                    tab.addEventListener('click', function () {
                        var target = this.getAttribute('data-doc-tab');
                        tabs.forEach(function (t) { t.classList.toggle('active', t === tab); });
                        panes.forEach(function (p) { p.classList.toggle('d-none', p.id !== 'doc-' + target); });
                    });
                });
            })();
            </script>

        @include('components.frontend.footer')

        @include('components.frontend.main-js')

    </body>

</html>
