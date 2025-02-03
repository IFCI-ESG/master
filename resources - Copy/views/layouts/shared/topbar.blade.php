<!-- Topbar Start -->
<div class="navbar-custom">
    <div class="topbar">
        <div class="topbar-menu d-flex align-items-center gap-1">

            <!-- LOGO -->
            <div class="logo-box">
                <a href="#" class="logo-light">
                    <img src="/images/logo/home-logo2.png" alt="logo" class="logo-lg">
                    <img src="/images/logo/home-logo2.png" alt="small logo" class="logo-sm">
                </a>
                <a href="#" class="logo-dark">
                    <img src="/images/logo/home-logo2.png" alt="dark logo" class="logo-lg">
                    <img src="/images/logo/home-logo2.png" alt="small logo" class="logo-sm">
                </a>
            </div>

            <button class="button-toggle-menu">
                <i class="mdi mdi-menu"></i>
            </button>
        </div>




        {{-- Right part of topnav --}}
        <ul class="topbar-menu d-flex align-items-center">
            <!-- Fullscreen Button -->
            <li class="d-none d-md-inline-block">
                <a class="nav-link waves-effect waves-light" data-toggle="fullscreen" href="#">
                    <i class="fe-maximize font-22"></i>
                </a>
            </li>
 
            <!-- Language flag dropdown  -->


            <!-- Light/Dark Mode Toggle Button -->
            <li class="d-none d-sm-inline-block">
                <div class="nav-link waves-effect waves-light" id="light-dark-mode">
                    <i class="ri-moon-line font-22"></i>
                </div>
            </li>

            <!-- User Dropdown -->
            <li class="dropdown">
                <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <img src="/images/user-profile.jpg" alt="user-image" class="rounded-circle">
                    <span class="ms-1 d-none d-md-inline-block">
                        {{Auth::user()->name}} <i class="mdi mdi-chevron-down"></i>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end profile-dropdown " data-popper-placement="bottom-end" style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate(0px, 72px);">
                    <!-- item-->
                    <div class="dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Welcome !</h6>
                    </div>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-user"></i>
                        <span>My Account</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-settings"></i>
                        <span>Settings</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-lock"></i>
                        <span>Lock Screen</span>
                    </a>

                    <div class="dropdown-divider"></div>

                    <!-- item-->
                    <form method="POST" action="{{ route('admin.logout') }}" style="margin-block-end: 0px;">
                        @csrf
                        <a href="javascript:void(0);" class="dropdown-item notify-item" onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="fe-log-out"></i>
                            <span>Logout</span>
                        </a>
                    </form>


                </div>
            </li>

            <li>
                <a href="#theme-settings-offcanvas" class="nav-link waves-effect waves-light" data-bs-toggle="offcanvas">
                    <i class="fe-settings font-22"></i>
                </a>
            </li>

        </ul>

    </div>

    <div class="container-fluid">

        <ul class="list-unstyled topnav-menu topnav-menu-left m-0">

            <li>
                <!-- Mobile menu toggle (Horizontal Layout)-->
                <a class="navbar-toggle nav-link" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                    <div class="lines">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </a>
                <!-- End mobile menu toggle-->
            </li>

        </ul>
        <div class="clearfix"></div>
    </div>
</div>
<!-- end Topbar -->
