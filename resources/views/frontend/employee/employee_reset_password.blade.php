<!doctype html>
<html lang="en">

    <head>
        @include('components.frontend.head')
    </head>

    <body>

        @include('components.frontend.header')

        <section class="pumping-forget-password--wrap">
      <div class="container">
        <div class="col-md-12">

          <div class="pumping-forget-password-box">
            <div class="pumping-forget-password-content">

              <!-- Icon -->
              <div class="pumping-forget-password-icon">
                <img src="{{ asset('frontend/assets/images/forgot-password.svg') }}" alt="Reset Password">
              </div>

              <h2>Reset Password</h2>

              <div class="pumping-forget-password-line"></div>

              <p class="pumping-forget-password-subtitle">
                Enter a new password for<br>
                <strong>{{ $email }}</strong>
              </p>

              <form action="{{ route('frontend.employee_update_password') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <!-- New Password -->
                <div class="pumping-forget-password-group">
                  <label for="pumping-password">New Password</label>
                  <div class="pumping-forget-password-input" style="position:relative;">
                    <input class="form-control" type="password" id="pumping-password" name="password"
                           placeholder="Enter new password" required style="padding-right:46px;" />
                    <span id="togglePassword" style="position:absolute;top:50%;right:14px;transform:translateY(-50%);cursor:pointer;color:#8a978f;display:flex;">
                        <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                    </span>
                  </div>
                  <small style="color:#8a978f;">At least 8 characters.</small>
                </div>

                <!-- Confirm Password -->
                <div class="pumping-forget-password-group">
                  <label for="pumping-password-confirm">Confirm New Password</label>
                  <div class="pumping-forget-password-input">
                    <input class="form-control" type="password" id="pumping-password-confirm" name="password_confirmation"
                           placeholder="Re-enter new password" required />
                  </div>
                </div>

                <!-- Submit -->
                <button type="submit" class="pumping-forget-password-submit btn">
                  <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M20 6L9 17l-5-5"></path>
                  </svg>
                  Reset Password
                </button>

                <!-- Divider -->
                <div class="pumping-forget-password-divider">
                  <span></span>
                  <small>or</small>
                  <span></span>
                </div>

                <!-- Back to Login -->
                <a href="{{ route('frontend.employee_login') }}" class="pumping-forget-password-back btn">
                  <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M19 12H5"></path>
                    <path d="M12 19l-7-7 7-7"></path>
                  </svg>
                  Back to Login
                </a>

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

                // Disable the button + show "Setting password..." on submit
                var form = document.querySelector('.pumping-forget-password-content form');
                if (form) {
                    form.addEventListener('submit', function () {
                        var btn = form.querySelector('button[type="submit"]');
                        if (btn) {
                            btn.disabled = true;
                            btn.style.opacity = '0.75';
                            btn.style.cursor = 'not-allowed';
                            btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Setting password...';
                        }
                    });
                }
            })();
        </script>

    </body>

</html>
