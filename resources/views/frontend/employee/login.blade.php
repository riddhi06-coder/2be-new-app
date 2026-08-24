<!doctype html>
<html lang="en">

    <head>
        @include('components.frontend.head')
    </head>

    <body>

        @include('components.frontend.header')

        <section class="pumping-login">
            <div class="container">
                <div class="col-md-12">
                    <div class="pumping-login-box">
                        <div class="pumping-login-content">
                        <h2>Welcome Back!</h2>

                        <div class="pumping-login-line"></div>

                        <p class="pumping-login-subtitle">Sign in to access your employee portal</p>

                        @if ($errors->any())
                            <div class="alert alert-danger" style="border-radius:10px;font-size:14px;">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <form action="{{ route('frontend.employee_authenticate') }}" method="POST">
                            @csrf
                            <!-- Email -->
                            <div class="pumping-login-group">
                            <label>Email Address</label>

                            <div class="pumping-login-input">
                                <input class="form-control" type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required autofocus />
                            </div>
                            </div>

                            <!-- Password -->
                            <div class="pumping-login-group">
                            <label>Password</label>

                            <div class="pumping-login-input" style="position:relative;">
                                <input class="form-control" type="password" name="password" id="pumping-password" placeholder="Enter your password" required style="padding-right:46px;" />
                                <span id="togglePassword" style="position:absolute;top:50%;right:14px;transform:translateY(-50%);cursor:pointer;color:#8a978f;display:flex;">
                                    <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                                </span>
                            </div>
                            </div>

                            <!-- Remember / Forgot -->
                            <div class="pumping-login-options">
                            <label class="pumping-login-remember">
                                <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }} />
                                <span>Remember me</span>
                            </label>

                            <a href="{{ route('frontend.employee_forgot_password') }}"> Forgot Password? </a>
                            </div>

                            <!-- Sign In -->
                            <div class="text-center">
                            <button type="submit" class="pumping-login-signin btn">
                            <i class="fa fa-lock"></i>
                            Sign In
                            </button>
                        </div>

                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @include('components.frontend.footer')

        @include('components.frontend.main-js')

        <script>
            (function () {
                var toggle = document.getElementById('togglePassword');
                var input = document.getElementById('pumping-password');
                var eyeOpen = document.getElementById('eyeOpen');
                var eyeClosed = document.getElementById('eyeClosed');
                if (toggle && input) {
                    toggle.addEventListener('click', function () {
                        var show = input.type === 'password';
                        input.type = show ? 'text' : 'password';
                        eyeOpen.style.display = show ? 'none' : 'flex';
                        eyeClosed.style.display = show ? 'flex' : 'none';
                    });
                }

                // Disable the button + show "Submitting..." on submit
                var form = document.querySelector('.pumping-login-content form');
                if (form) {
                    form.addEventListener('submit', function () {
                        var btn = form.querySelector('button[type="submit"]');
                        if (btn) {
                            btn.disabled = true;
                            btn.style.opacity = '0.75';
                            btn.style.cursor = 'not-allowed';
                            btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Logging in...';
                        }
                    });
                }
            })();
        </script>

    </body>

</html>
