<!-- ========== Left Sidebar Start ========== -->
<div class="app-menu">

    <div class="logo-box">
        <a href="#" class="logo-light">
            <img src="/images/logo/home-logo2.png" alt="logo" class="logo-lg"  style="height: 40px;">
            <img src="/images/logo-sm.png" alt="small logo" class="logo-sm" style="height: 40px;">
        </a>
        <a href="#" class="logo-dark">
            <img src="/images/logo/home-logo2.png" alt="dark logo" class="logo-lg" style="height: 40px;">
            <img src="/images/logo/home-logo2.png" alt="small logo" class="logo-sm"style="height: 40px;">
        </a>
    </div>

    <div class="scrollbar h-100" data-simplebar>

        <!-- User box -->
        <div class="user-box text-center">
            <img src="/images/user-profile.jpg" alt="user-img" title="Mat Helme" class="rounded-circle avatar-md">
            <div class="dropdown">
                <a href="javascript: void(0);" class="dropdown-toggle h5 mb-1 d-block" data-bs-toggle="dropdown">{{Auth::user()->name}}</a>
                <div class="dropdown-menu user-pro-dropdown">

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-user me-1"></i>
                        <span>My Account</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-settings me-1"></i>
                        <span>Settings</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-lock me-1"></i>
                        <span>Lock Screen</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-log-out me-1"></i>
                        <span>Logout</span>
                    </a>

                </div>
            </div>
            <p class="text-muted mb-0">Admin Head</p>
        </div>

        <!--- Sidemenu -->

        <ul id="side-menu" class="menu">
            <li class="menu-title">Navigation</li>
            <li class="menu-item ">
                <a href="{{ route('admin.dash') }}" class="menu-link">
                    <span class="menu-icon"><i data-feather="airplay"></i></span>
                    <span class="menu-text"> Dashboards </span>
                </a>
            </li>
            <li class="menu-title">Apps</li>
            <li class="menu-item">
                <a href="{{ route('admin.env_mis') }}" class="menu-link">
                    <span class="menu-icon"><i data-feather="calendar"></i></span>
                    <span class="menu-text"> MIS </span>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{route('admin.bank_branch_bulk.create')}}" class="menu-link">
                    <span class="menu-icon"><i data-feather="plus-circle"></i></span>
                    <span class="menu-text"> Add Branch </span>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{route('admin.bank_branch_bulk.index')}}" class="menu-link">
                    <span class="menu-icon"><i data-feather="plus-circle"></i></span>
                    <span class="menu-text"> View Branch </span>
                </a>
            </li>
            <li class="menu-item ">
            <a class="menu-link" href="#sidebarExposure" data-bs-toggle="collapse">
                    <span class="menu-icon"><i data-feather="users"></i></span>
                    <span class="menu-text"> Exposure </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarExposure">
                    <ul class="sub-menu">
             
                        <li class="menu-item">
                            <a href="{{ route('admin.user.bulk.company.create') }}" class="menu-link"><span class="menu-text">Bulk Exposure</span></a>
                        </li>
                        <li class="menu-item ">
                            <a href="{{ route('admin.user.index') }}" class="menu-link"><span
                                    class="menu-text">View Exposure</span></a>
                        </li>
                         <li class="menu-item ">
                            <a href="{{ route('admin.adduser') }}" class="menu-link"><span
                                    class="menu-text">Add Exposure</span></a>
                        </li>
                     
                    </ul>
                </div>
            </li>

            <li class="menu-item ">
            <a class="menu-link" href="#sidebarBank" data-bs-toggle="collapse">
                    <span class="menu-icon"><i data-feather="users"></i></span>
                    <span class="menu-text"> Bank </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarBank">
                    <ul class="sub-menu">
                        <li class="menu-item">
                            <a href="{{ route('admin.new_admin.create') }}" class="menu-link"><span class="menu-text">Add Bank</span></a>
                        </li>
                        <li class="menu-item ">
                            <a href="{{ route('admin.new_admin.index') }}" class="menu-link"><span
                                    class="menu-text">View Bank</span></a>
                        </li>
                     
                    </ul>
                </div>
            </li>


        </ul>

        {{-- </div> --}}
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
<!-- Left Sidebar End -->
