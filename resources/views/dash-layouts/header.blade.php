<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="#" class="logo logo-dark">
                    <span class="logo-sm">
                        <h3> {{ __('WorkShop') }} </h3>
                    </span>
                    <span class="logo-lg">
                        <h3> {{ __('WorkShop') }} </h3>
                    </span>
                </a>

                <a href="#" class="logo logo-light">
                    <span class="logo-sm">
                        <h3> {{ __('WorkShop') }} </h3>
                    </span>
                    <span class="logo-lg">
                        <h3> {{ __('WorkShop') }} </h3>
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                <i class="mdi mdi-menu"></i>
            </button>
        </div>

        <div class="d-flex">

            <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                <a class="btn btn-primary dropdown-toggle" style="padding: 6px 34px" href="{{ route('main') }}"> {{ __('WorkShop') }} </a>
            </button>

            <div class="dropdown d-none d-md-block ms-2">
                <button type="button" class="btn header-item waves-effect" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @if(App::isLocale('ar'))
                    <img class="me-2" src="{{ asset('dash/assets/images/flags/sr_flag.png') }}" alt="Header Language" height="20"> العربية <span class="mdi mdi-chevron-down"></span>
                    @else
                    <img class="me-2" src="{{ asset('dash/assets/images/flags/us_flag.jpg') }}" alt="Header Language" height="20"> English <span class="mdi mdi-chevron-down"></span>
                    @endif
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <a href="{{ route('lang.switch', 'ar') }}" class="dropdown-item notify-item">
                        <img src="{{ asset('dash/assets/images/flags/sr_flag.png') }}" alt="user-image" class="me-1" height="12"> <span class="align-middle"> العربية </span>
                    </a>
                    <a href="{{ route('lang.switch', 'en')}}" class="dropdown-item notify-item">
                        <img src="{{ asset('dash/assets/images/flags/us_flag.jpg') }}" alt="user-image" class="me-1" height="12"> <span class="align-middle"> English </span>
                    </a>
                </div>
            </div>

            <div class="dropdown d-none d-lg-inline-block">
                <button type="button" class="btn header-item noti-icon waves-effect" data-bs-toggle="fullscreen">
                    <i class="mdi mdi-fullscreen"></i>
                </button>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button"  class="btn header-item waves-effect" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user" src="{{ asset('dash/assets/images/users/user-4.jpg') }}" alt="Header Avatar">
                </button>
                <div class="dropdown-menu dropdown-menu-end">

                    {{-- <div class="dropdown-divider"></div>  --}}
                    <a class="dropdown-item text-danger" id="logout" href="{{ route('logout') }}"><i class="bx bx-power-off font-size-17 align-middle me-1 text-danger"></i> Logout</a>
                </div>
            </div>



        </div>
    </div>
</header>
