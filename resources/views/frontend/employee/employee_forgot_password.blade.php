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
                <img src="{{ asset('frontend/assets/images/forgot-password.svg') }}" alt="Forgot Password">
              </div>

              <h2>Forgot Password?</h2>

              <div class="pumping-forget-password-line"></div>

              <p class="pumping-forget-password-subtitle">
                No worries! Enter your email address and we'll<br>
                send you a link to reset your password.
              </p>

              <form action="{{ route('frontend.employee_send_reset_link') }}" method="POST">
                @csrf

                <!-- Email -->
                <div class="pumping-forget-password-group">
                  <label for="pumping-email">Email Address</label>

                  <div class="pumping-forget-password-input">
                    <input
                      class="form-control"
                      type="email"
                      id="pumping-email"
                      name="email"
                      value="{{ old('email') }}"
                      placeholder="Enter your email address"
                      required
                    />
                  </div>
                </div>

                <!-- Submit -->
                <button
                  type="submit"
                  class="pumping-forget-password-submit btn"
                >
                  <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M22 2L11 13"></path>
                    <path d="M22 2l-7 20-4-9-9-4z"></path>
                  </svg>

                  Send Reset Link
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

          <!-- Security Notice -->
          <div class="pumping-forget-password-security">

            <div class="pumping-forget-password-security-icon">
              <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M12 3l7 3v5c0 5-3 8-7 10-4-2-7-5-7-10V6l7-3z"></path>
                <path d="M9 12l2 2 4-4"></path>
              </svg>
            </div>

            <div class="pumping-forget-password-security-content">
              <strong>Security Note</strong>
              <p>
                For your security, the password reset link will expire in 60 minutes.<br>
                If you don't receive the email, please check your spam folder.
              </p>
            </div>

          </div>

        </div>
      </div>
    </section>




        @include('components.frontend.footer')

        @include('components.frontend.main-js')

        <script>
            (function () {
                var form = document.querySelector('.pumping-forget-password-content form');
                if (form) {
                    form.addEventListener('submit', function () {
                        var btn = form.querySelector('button[type="submit"]');
                        if (btn) {
                            btn.disabled = true;
                            btn.style.opacity = '0.75';
                            btn.style.cursor = 'not-allowed';
                            btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Sending...';
                        }
                    });
                }
            })();
        </script>

    </body>

</html>
