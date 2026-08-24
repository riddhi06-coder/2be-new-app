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

                        <form>
                            <!-- Email -->
                            <div class="pumping-login-group">
                            <label>Email Address</label>

                            <div class="pumping-login-input">

                                <input class="form-control" type="email" placeholder="Enter your email" />
                            </div>
                            </div>

                            <!-- Password -->
                            <div class="pumping-login-group">
                            <label>Password</label>

                            <div class="pumping-login-input">
                                <input class="form-control" type="password" id="pumping-password" placeholder="Enter your password" />
                            </div>
                            </div>

                            <!-- Remember / Forgot -->
                            <div class="pumping-login-options">
                            <label class="pumping-login-remember">
                                <input type="checkbox" />
                                <span>Remember me</span>
                            </label>

                            <a href="#"> Forgot Password? </a>
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

    </body>

</html>
