
        <section class="navigation">
            <div class="container-fluid">
                <nav class="navbar navbar-expand-lg">
                <!-- Logo -->
                <div class="logo">
                    <a href="{{ route('frontend.employee_portal') }}">
                        <img src="{{ asset('frontend/assets/images/logo.webp') }}" class="img-responsive mb-2" alt="2BE Pumping Log">
                    </a>
                </div>
                <!-- Navigation Menu -->
                <div class="collapse navbar-collapse nav-menu" id="mainNavbar">
                    <ul class="navbar-nav mx-auto">
                    <!-- Home -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('frontend.employee_portal') }}"> HOME </a>
                    </li>
                    @auth
                    <!-- Dashboard (logged-in only) -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('frontend.employee_dashboard') }}"> DASHBOARD </a>
                    </li>
                    @endauth

                    <!-- Documents -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('frontend.employee_documents') }}"> DOCUMENT LIBRARY </a>
                    </li>
                 
                    @auth
                    <!-- Incident Report (logged-in only) -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('frontend.employee_incident_report') }}"> INCIDENT REPORT </a>
                    </li>
                    @endauth


                    <!-- Announcements -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('frontend.employee_announcements') }}"> ANNOUNCEMENTS </a>
                    </li>
                  
                    <!-- Community Calendar -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('frontend.employee_calendar') }}"> COMMUNITY CALENDAR </a>
                    </li>
                    </ul>
                </div>
                <!-- Right Side -->
                <div class="header-right">
                    @auth
                    <!-- Logged-in: account dropdown -->
                    <div class="employee-dropdown dropdown">
                    <a
                        href="#"
                        class="employee-link dropdown-toggle"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >
                        <span class="user-icon">
                        <i class="fa fa-user"> </i>
                        </span>
                        <span class="employee-text">
                        <small> Welcome, </small>
                        <strong> {{ auth()->user()->name }} </strong>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                        <a class="dropdown-item" href="{{ route('frontend.employee_dashboard') }}">
                            <i class="fa fa-th-large"></i>
                            My Dashboard
                        </a>
                        </li>
                        <li>
                        <a class="dropdown-item" href="#">
                            <i class="fa fa-user"></i>
                            My Profile
                        </a>
                        </li>
                        <li>
                        <hr class="dropdown-divider" />
                        </li>
                        <li>
                        <a class="dropdown-item" href="{{ route('frontend.employee_logout') }}">
                            <i class="fa fa-sign-out"></i>
                            Logout
                        </a>
                        </li>
                    </ul>
                    </div>
                    @else
                    <!-- Logged-out: login button -->
                    <a href="{{ route('frontend.employee_login') }}" class="employee-link employee-login-btn">
                        <span class="user-icon"><i class="fa fa-sign-in"></i></span>
                        <span class="employee-text"><strong>Employee Login</strong></span>
                    </a>
                    @endauth
                    <!-- Mobile Toggle -->
                    <button
                    class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#mainNavbar"
                    aria-controls="mainNavbar"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                    >
                    <i class="fa fa-bars"></i>
                    </button>
                </div>
                </nav>
            </div>
        </section>