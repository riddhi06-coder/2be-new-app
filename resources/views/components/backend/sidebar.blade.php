<!-- Page Body Start-->
 <div class="page-body-wrapper">
        <!-- Page Sidebar Start-->
        <div class="sidebar-wrapper" data-layout="stroke-svg">
          <div class="logo-wrapper"><a href="{{ route('admin.dashboard') }}"><img class="img-fluid sidebar-full-logo" src="{{ asset('admin/assets/images/logo/logo.webp') }}" alt="2B Environmental"></a>
		  <div class="back-btn mt-5"><i class="fa fa-angle-left"> </i></div>
            <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div>
          </div>
          <div class="logo-icon-wrapper"><a href="{{ route('admin.dashboard') }}"><img class="img-fluid sidebar-icon-logo" src="{{ asset('admin/assets/images/logo/logo.webp') }}" alt="2B"></a></div>
          <nav class="sidebar-main">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="sidebar-menu">
              <ul class="sidebar-links" id="simple-bar">

              
                <li class="back-btn"><a href="{{ route('admin.dashboard') }}"><img class="img-fluid" src="{{ asset('admin/assets/images/favicon.ico') }}" alt="" style="max-width: 40%; margin-right:15px;"></a>
                  <div class="mobile-back text-end"> <span>Back </span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
                </li>


                @php
                    $u   = auth()->user();
                    $can = fn (string $permission) => (bool) $u?->hasPermission($permission);
                @endphp

                @if($can('dashboard.view'))
                <li class="sidebar-list mt-5 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                  <i class="fa fa-thumb-tack"> </i>
                  <a class="sidebar-link sidebar-title link-nav" href="{{ route('admin.dashboard') }}">
                    <svg class="stroke-icon">
                      <use href="{{ asset('admin/assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                    </svg>
                    <svg class="fill-icon">
                      <use href="{{ asset('admin/assets/svg/icon-sprite.svg#fill-home') }}"></use>
                    </svg>
                    <span class="lan-3">Dashboard</span>
                  </a>
                </li>
                @endif


                @if($can('roles.view') || $can('users.view') || $can('permissions.view'))
                <li class="sidebar-list {{ request()->routeIs('admin.roles.*', 'admin.users.*', 'admin.permissions.*') ? 'active' : '' }}">
                  <i class="fa fa-thumb-tack"></i>
                  <a class="sidebar-link sidebar-title" href="#">
                    <svg class="stroke-icon">
                      <use href="{{ asset('admin/assets/svg/icon-sprite.svg#stroke-user') }}"></use>
                    </svg>
                    <svg class="fill-icon">
                      <use href="{{ asset('admin/assets/svg/icon-sprite.svg#fill-user') }}"></use>
                    </svg>
                    <span>User Management</span>
                  </a>
                  <ul class="sidebar-submenu">
                      @if($can('roles.view'))
                          <li><a href="{{ route('admin.roles.index') }}" class="{{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">Roles</a></li>
                      @endif
                      @if($can('users.view'))
                          <li><a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Users</a></li>
                      @endif
                      @if($can('permissions.view'))
                          <li><a href="{{ route('admin.permissions.index') }}" class="{{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">Permissions</a></li>
                      @endif
                  </ul>
                </li>
                @endif


                @if($can('manage-disposal-details.view'))
                 <li class="sidebar-list {{ request()->routeIs('manage-disposal-details.index') ? 'active' : '' }}">
                  <i class="fa fa-thumb-tack"></i>
                  <a class="sidebar-link" href="{{ route('manage-disposal-details.index') }}">
                    <svg class="stroke-icon">
                      <use href="{{ asset('admin/assets/svg/icon-sprite.svg#return-box') }}"></use>
                    </svg>
                    <svg class="fill-icon">
                      <use href="{{ asset('admin/assets/svg/icon-sprite.svg#return-box') }}"></use>
                    </svg>
                    <span>Disposal Details</span>
                  </a>
                </li>
                @endif


                @if($can('manage-email-settings.view'))
                <li class="sidebar-list {{ request()->routeIs('manage-email-settings.index') ? 'active' : '' }}">
                  <i class="fa fa-thumb-tack"></i>
                  <a class="sidebar-link" href="{{ route('manage-email-settings.index') }}">
                    <svg class="stroke-icon">
                      <use href="{{ asset('admin/assets/svg/icon-sprite.svg#doller-return') }}"></use>
                    </svg>
                    <svg class="fill-icon">
                      <use href="{{ asset('admin/assets/svg/icon-sprite.svg#doller-return') }}"></use>
                    </svg>
                    <span>Email Setting</span>
                  </a>
                </li>
                @endif


                @if($can('cesspool-records.view'))
                <li class="sidebar-list {{ request()->routeIs('cesspool-records.*') ? 'active' : '' }}">
                  <i class="fa fa-thumb-tack"></i>
                  <a class="sidebar-link" href="{{ route('cesspool-records.index') }}">
                    <svg class="stroke-icon">
                      <use href="{{ asset('admin/assets/svg/icon-sprite.svg#stroke-file') }}"></use>
                    </svg>
                    <svg class="fill-icon">
                      <use href="{{ asset('admin/assets/svg/icon-sprite.svg#fill-file') }}"></use>
                    </svg>
                    <span>Cesspool Records</span>
                  </a>
                </li>
                @endif


                @if($can('septic-records.view'))
                <li class="sidebar-list {{ request()->routeIs('septic-records.*') ? 'active' : '' }}">
                  <i class="fa fa-thumb-tack"></i>
                  <a class="sidebar-link" href="{{ route('septic-records.index') }}">
                    <svg class="stroke-icon">
                      <use href="{{ asset('admin/assets/svg/icon-sprite.svg#stroke-knowledgebase') }}"></use>
                    </svg>
                    <svg class="fill-icon">
                      <use href="{{ asset('admin/assets/svg/icon-sprite.svg#fill-knowledgebase') }}"></use>
                    </svg>
                    <span>Septic Records</span>
                  </a>
                </li>
                @endif


                @if($can('employees.view'))
                <li class="sidebar-main-title">
                  <div><h6>HR Portal</h6></div>
                </li>

                <li class="sidebar-list {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}">
                  <i class="fa fa-thumb-tack"></i>
                  <a class="sidebar-link" href="{{ route('admin.employees.index') }}">
                    <svg class="stroke-icon">
                      <use href="{{ asset('admin/assets/svg/icon-sprite.svg#stroke-user') }}"></use>
                    </svg>
                    <svg class="fill-icon">
                      <use href="{{ asset('admin/assets/svg/icon-sprite.svg#fill-user') }}"></use>
                    </svg>
                    <span>Employees</span>
                  </a>
                </li>
                @endif


                @if($can('document-categories.view') || $can('documents.view'))
                <li class="sidebar-list {{ request()->routeIs('admin.document-categories.*', 'admin.documents.*') ? 'active' : '' }}">
                  <i class="fa fa-thumb-tack"></i>
                  <a class="sidebar-link sidebar-title" href="#">
                    <svg class="stroke-icon">
                      <use href="{{ asset('admin/assets/svg/icon-sprite.svg#stroke-file') }}"></use>
                    </svg>
                    <svg class="fill-icon">
                      <use href="{{ asset('admin/assets/svg/icon-sprite.svg#fill-file') }}"></use>
                    </svg>
                    <span>Documents</span>
                  </a>
                  <ul class="sidebar-submenu">
                      @if($can('document-categories.view'))
                          <li><a href="{{ route('admin.document-categories.index') }}" class="{{ request()->routeIs('admin.document-categories.*') ? 'active' : '' }}">Folders</a></li>
                      @endif
                      @if($can('documents.view'))
                          <li><a href="{{ route('admin.documents.index') }}" class="{{ request()->routeIs('admin.documents.*') ? 'active' : '' }}">Documents</a></li>
                      @endif
                  </ul>
                </li>
                @endif


                @if($can('announcements.view'))
                <li class="sidebar-list {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
                  <i class="fa fa-thumb-tack"></i>
                  <a class="sidebar-link" href="{{ route('admin.announcements.index') }}">
                    <svg class="stroke-icon">
                      <use href="{{ asset('admin/assets/svg/icon-sprite.svg#stroke-blog') }}"></use>
                    </svg>
                    <svg class="fill-icon">
                      <use href="{{ asset('admin/assets/svg/icon-sprite.svg#fill-blog') }}"></use>
                    </svg>
                    <span>Announcements</span>
                  </a>
                </li>
                @endif


                @if($can('incident-reports.view'))
                <li class="sidebar-list {{ request()->routeIs('admin.incident-reports.*') ? 'active' : '' }}">
                  <i class="fa fa-thumb-tack"></i>
                  <a class="sidebar-link" href="{{ route('admin.incident-reports.index') }}">
                    <svg class="stroke-icon">
                      <use href="{{ asset('admin/assets/svg/icon-sprite.svg#stroke-support-tickets') }}"></use>
                    </svg>
                    <svg class="fill-icon">
                      <use href="{{ asset('admin/assets/svg/icon-sprite.svg#fill-support-tickets') }}"></use>
                    </svg>
                    <span>Incident Reports</span>
                  </a>
                </li>
                @endif


                @if($can('calendar.view'))
                <li class="sidebar-list {{ request()->routeIs('admin.community-calendar.*') ? 'active' : '' }}">
                  <i class="fa fa-thumb-tack"></i>
                  <a class="sidebar-link" href="{{ route('admin.community-calendar.index') }}">
                    <svg class="stroke-icon">
                      <use href="{{ asset('admin/assets/svg/icon-sprite.svg#stroke-calendar') }}"></use>
                    </svg>
                    <svg class="fill-icon">
                      <use href="{{ asset('admin/assets/svg/icon-sprite.svg#fill-calender') }}"></use>
                    </svg>
                    <span>Community Calendar</span>
                  </a>
                </li>
                @endif


              </ul>
              <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
            </div>
          </nav>
        </div>


        