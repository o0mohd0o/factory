<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">{{ __('Main') }}</li>
                <li>
                    <a href="#" class="waves-effect" id="dashboardButton">
                        <i class="ti-home"></i>
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                </li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ti-user"></i>
                        <span>{{ __('Users') }}</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li>
                            <a href="#" id="manage-users" data-type="manage-users" data-url="{{ route('ajax.manageUsers.index') }}">
                               {{ __('Manage Users') }}
                            </a>
                        </li>
                        <li>
                            <a href="#" id="addNewUser" data-type="addNewUser" data-url="{{ route('ajax.newUser.index') }}">
                                {{ __('Add New User') }}
                            </a>
                        </li>
                    </ul>
                </li>


            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
