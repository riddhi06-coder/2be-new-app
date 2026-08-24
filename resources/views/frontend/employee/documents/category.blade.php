<!doctype html>
<html lang="en">

    <head>
        @include('components.frontend.head')
    </head>

    <body>

        @include('components.frontend.employee_header')

        @php
            // Map a file extension to an icon + colour class.
            $iconFor = function ($doc) {
                $ext = strtolower(pathinfo($doc->original_name ?: $doc->file_path, PATHINFO_EXTENSION));
                return match ($ext) {
                    'pdf'                 => ['fa-file-pdf-o',   'ft-pdf'],
                    'doc', 'docx'         => ['fa-file-word-o',  'ft-doc'],
                    'xls', 'xlsx', 'csv'  => ['fa-file-excel-o', 'ft-xls'],
                    'ppt', 'pptx'         => ['fa-file-powerpoint-o', 'ft-ppt'],
                    'jpg', 'jpeg', 'png', 'gif', 'webp' => ['fa-file-image-o', 'ft-img'],
                    'zip', 'rar'          => ['fa-file-archive-o', 'ft-zip'],
                    default               => ['fa-file-o', 'ft-file'],
                };
            };
        @endphp

            <section class="pumping-log">
                <div class="container">
                    <div class="col-md-12">
                    <div class="pumping-log__content">
                        <h1 class="pumping-log__title">{{ $category->name }}</h1>
                        <p class="pumping-log__description">
                        {{ $category->description ?: 'Documents available in this category.' }}
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
                    <a href="{{ route('frontend.employee_documents') }}">Document Library</a>
                    <i class="fa fa-angle-right"></i>
                    <span class="current">{{ $category->name }}</span>
                </div>

                @if($publicDocs->isEmpty() && $personalDocs->isEmpty())
                    <div class="emp-empty">
                        <i class="fa fa-folder-open-o"></i>
                        <p>There are no documents in this category yet.</p>
                        <a href="{{ route('frontend.employee_documents') }}" class="emp-empty__btn">Back to Library</a>
                    </div>
                @endif

                @if($publicDocs->isNotEmpty())
                    <div class="doc-group" id="shared">
                        <h2 class="doc-group__title"><i class="fa fa-users"></i> Shared Documents</h2>
                        <div class="doc-list">
                            @foreach($publicDocs as $doc)
                                @php [$icon, $ftClass] = $iconFor($doc); @endphp
                                <div class="doc-item">
                                    <span class="doc-item__icon {{ $ftClass }}"><i class="fa {{ $icon }}"></i></span>
                                    <div class="doc-item__info">
                                        <span class="doc-item__title">{{ $doc->title }}</span>
                                        <span class="doc-item__meta">{{ $doc->original_name }} &middot; {{ $doc->readable_size }}</span>
                                    </div>
                                    <span class="doc-item__badge is-shared">Shared</span>
                                    <a href="{{ route('frontend.employee_document_download', $doc) }}" class="doc-item__btn">
                                        <i class="fa fa-download"></i> <span>Download</span>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($personalDocs->isNotEmpty())
                    <div class="doc-group" id="personal">
                        <h2 class="doc-group__title"><i class="fa fa-user"></i> My Personal Documents</h2>
                        <div class="doc-list">
                            @foreach($personalDocs as $doc)
                                @php [$icon, $ftClass] = $iconFor($doc); @endphp
                                <div class="doc-item">
                                    <span class="doc-item__icon {{ $ftClass }}"><i class="fa {{ $icon }}"></i></span>
                                    <div class="doc-item__info">
                                        <span class="doc-item__title">{{ $doc->title }}</span>
                                        <span class="doc-item__meta">{{ $doc->original_name }} &middot; {{ $doc->readable_size }}</span>
                                    </div>
                                    <span class="doc-item__badge is-personal">Personal</span>
                                    <a href="{{ route('frontend.employee_document_download', $doc) }}" class="doc-item__btn">
                                        <i class="fa fa-download"></i> <span>Download</span>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            </section>

        @include('components.frontend.footer')

        @include('components.frontend.main-js')

    </body>

</html>
