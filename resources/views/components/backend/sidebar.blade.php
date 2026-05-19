<!-- Page Body Start-->
 <div class="page-body-wrapper">
        <!-- Page Sidebar Start-->
        <div class="sidebar-wrapper" data-layout="stroke-svg" style="width:20%;">
          <div class="logo-wrapper"><a href="{{ route('admin.dashboard') }}"><img class="img-fluid" src="{{ asset('/admin/assets/images/logo/logo.webp') }}" alt="" style="max-width: 50% !important; margin-left:40px;"></a>
		  	<a href="{{ route('admin.dashboard') }}">
				<!-- <img class="img-fluid" src="{{ asset('admin/assets/images/logo/logo-icon.png') }}" alt="" style="max-width: 65% !important;"> -->
			</a>  
		  <div class="back-btn mt-5"><i class="fa fa-angle-left"> </i></div>
            <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div>
          </div>
          <div class="logo-icon-wrapper"><a href="{{ route('admin.dashboard') }}"><img class="img-fluid" src="{{ asset('admin/assets/images/favicon.ico') }}" alt=""  style="max-width: 20% !important;margin-reight:50px !important;"></a></div>
          <nav class="sidebar-main">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="sidebar-menu">
              <ul class="sidebar-links" id="simple-bar">
                <li class="back-btn"><a href="{{ route('admin.dashboard') }}"><img class="img-fluid" src="{{ asset('admin/assets/images/logo//images/favicon.ico') }}" alt="" style="max-width: 40% !important; margin-right:15px;"></a>
                  <div class="mobile-back text-end"> <span>Back </span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
                </li>

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


               
              </ul>
              <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
            </div>
          </nav>
        </div>


        