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
                        <span class="pumping-log__brand">Read</span>
                        &amp; Sign
                        </h1>
                        <p class="pumping-log__description">
                        Please read the document below, then sign to confirm you have received and understood it.
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
                    <a href="{{ route('frontend.employee_dashboard') }}">Dashboard</a>
                    <i class="fa fa-angle-right"></i>
                    <a href="{{ route('frontend.employee_documents') }}">Document Library</a>
                    <i class="fa fa-angle-right"></i>
                    <span class="current">Read &amp; Sign</span>
                </div>

                <div class="sign-doc">
                    <div class="sign-doc__head">
                        <h2 class="sign-doc__title"><i class="fa fa-file-pdf-o"></i> {{ $document->title }}</h2>
                        @if($document->acknowledgment_due)
                            <span class="sign-doc__due {{ $document->acknowledgment_due->isPast() ? 'is-overdue' : '' }}">
                                <i class="fa fa-clock-o"></i> Sign by {{ $document->acknowledgment_due->format('M j, Y') }}{{ $document->acknowledgment_due->isPast() ? ' (overdue)' : '' }}
                            </span>
                        @endif
                    </div>

                    <div class="sign-doc__steps">
                        <span class="sign-doc__step"><b>1</b> Read the document below</span>
                        <i class="fa fa-angle-right"></i>
                        <span class="sign-doc__step"><b>2</b> Sign at the bottom</span>
                        <a href="#signHere" class="sign-doc__jump">Skip to signature <i class="fa fa-arrow-down"></i></a>
                    </div>

                    <!-- Blurred, locked preview: content is protected until the employee signs -->
                    <div class="sign-doc__viewer is-blurred">
                        <iframe src="{{ asset($document->file_path) }}#toolbar=0&navpanes=0&scrollbar=0&view=FitH"
                                title="{{ $document->title }}"></iframe>
                        <div class="sign-doc__lock">
                            <i class="fa fa-lock"></i>
                            <span class="sign-doc__lock-title">Protected preview</span>
                            <span class="sign-doc__lock-sub">Sign below to unlock and receive your copy of this document.</span>
                        </div>
                    </div>

                    <!-- Sign form -->
                    <form action="{{ route('frontend.employee_document_acknowledge', $document) }}" method="POST" class="sign-doc__form" id="signHere">
                        @csrf
                        <h3 class="sign-doc__form-title"><i class="fa fa-pencil-square-o"></i> Sign to confirm</h3>

                        <div class="sign-doc__statement">
                            <label class="sign-doc__check">
                                <input type="checkbox" name="agree" value="1" {{ old('agree') ? 'checked' : '' }}>
                                <span>I confirm that I have <strong>read and understood</strong> this document.</span>
                            </label>
                            @error('agree')<div class="sign-doc__error">{{ $message }}</div>@enderror
                        </div>

                        <div class="sign-doc__field">
                            <label for="signed_name">Type your full name to sign <span class="req">*</span></label>
                            <input type="text" id="signed_name" name="signed_name" value="{{ old('signed_name', auth()->user()->name ?? '') }}"
                                   placeholder="e.g. {{ auth()->user()->name ?? 'Your full name' }}" autocomplete="off" required>
                            @error('signed_name')<div class="sign-doc__error">{{ $message }}</div>@enderror
                            <p class="sign-doc__hint">Your typed name is your electronic signature. It will be recorded with the date, time and your IP address.</p>
                        </div>

                        <div class="sign-doc__actions">
                            <button type="submit" class="sign-doc__submit" id="signSubmitBtn"><i class="fa fa-check"></i> Acknowledge &amp; Sign</button>
                            <a href="{{ route('frontend.employee_documents') }}" class="sign-doc__cancel">Cancel</a>
                        </div>
                    </form>
                    <script>
                        (function () {
                            var form = document.getElementById('signHere');
                            var btn  = document.getElementById('signSubmitBtn');
                            if (!form || !btn) { return; }
                            form.addEventListener('submit', function () {
                                // Let native validation (required name / checkbox) run first.
                                if (!form.checkValidity || form.checkValidity()) {
                                    btn.disabled = true;
                                    btn.style.opacity = '0.75';
                                    btn.style.cursor = 'wait';
                                    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Submitting…';
                                }
                            });
                        })();
                    </script>
                </div>
            </div>
            </section>

        @include('components.frontend.footer')

        @include('components.frontend.main-js')

    </body>

</html>
