
        <section class="navigation">
            <div class="container-fluid">
                <nav class="navbar navbar-expand-lg">
                <!-- Logo -->
                <div class="logo">
                    <a href="{{ route('frontend.index') }}">
                        <img src="{{ asset('frontend/assets/images/logo.webp') }}" class="img-responsive mb-2" alt="2BE Pumping Log">
                    </a>
                </div>
                <!-- Navigation Menu -->
                <div class="collapse navbar-collapse nav-menu" id="mainNavbar">
                    <ul class="navbar-nav mx-auto">
                    <!-- Home -->
                    <li class="nav-item">
                        <a class="nav-link active" href="index.html"> HOME </a>
                    </li>
                    <!-- Employee Handbook -->
                    <li class="nav-item">
                        <a class="nav-link" href="#"> EMPLOYEE HANDBOOK </a>
                    </li>
                    <!-- Dropdown Menu -->
                    <li class="nav-item dropdown">
                        <a
                        class="nav-link dropdown-toggle"
                        href="#"
                        id="safetyDropdown"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                        >
                        SAFETY PROGRAMS
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="safetyDropdown">
                        <li>
                            <a class="dropdown-item" href="#"> Safety Training </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#"> Safety Documents </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#"> Emergency Procedures </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider" />
                        </li>
                        <li>
                            <a class="dropdown-item" href="#"> Safety Reports </a>
                        </li>
                        </ul>
                    </li>
                    <!-- Incident Report -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('frontend.employee_incident_report') }}"> INCIDENT REPORT </a>
                    </li>
                    <!-- Labor Poster Laws -->
                    <li class="nav-item">
                        <a class="nav-link" href="#"> LABOR POSTER LAWS </a>
                    </li>
                    <!-- Team Calendar -->
                    <li class="nav-item">
                        <a class="nav-link" href="#"> TEAM CALENDAR </a>
                    </li>
                    </ul>
                </div>
                <!-- Right Side -->
                <div class="header-right">
                    <!-- Employee Login -->
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
                        <strong> {{ auth()->check() ? auth()->user()->name : 'Employee' }} </strong>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                        <a class="dropdown-item" href="#">
                            <i class="fa fa-user"></i>
                            My Profile
                        </a>
                        </li>
                        <li>
                        <a class="dropdown-item" href="#">
                            <i class="fa fa-cog"></i>
                            Settings
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