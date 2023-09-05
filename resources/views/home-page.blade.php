@include('includes.header')


<div id="wrapper" class="home-wrapper" style="background: url({{ asset('images/bg.jpg') }})">
    <div id="content-wrapper">

        <div class="container-fluid">
            <div class="row">
                <div class="col-12" id="reports">
                    <ul>
                        <li class="dropdown" class=" text-center">
                            {{ __('Settings') }}
                            <ul class="dropdown-menu">
                                <li  data-type="general-settings" data-bs-toggle="modal"
                                    data-bs-target="#general-settings-query" id="general-settings">
                                    {{ __('General Settings') }}</li>
                                <li  data-type="item-card-settings"
                                    data-url="{{ route('ajax.itemCardSettings.show') }}" id="item-card-settings">
                                    {{ __('Item Card Settings') }}</li>

                            </ul>

                        </li>
                        <li class="dropdown" class="text-center"
                            data-flip="false">
                            {{ __('Reports') }}
                            <ul class="dropdown-menu">
                                <li  data-type="department-karat-difference-report" data-bs-toggle="modal"
                                    data-bs-target="#department-report-karat-difference-query">
                                    {{ __('Purity Differnce') }}</li>
                                <li  data-type="department-daily-report-in-total" data-bs-toggle="modal"
                                    data-bs-target="#department-daily-report-in-total-query">{{ __('Balance Summary') }}
                                </li>
                                <li  data-type="department-report" data-bs-toggle="modal"
                                    data-bs-target="#department-report-query">{{ __('Depts Statements') }}</li>
                                <li  data-type="department-daily-report" data-bs-toggle="modal"
                                    data-bs-target="#department-daily-report-query">{{ __('Daily Journal') }}</li>

                            </ul>

                        </li>
                        <li class="me-4" data-create-url="{{ route('ajax.qrcodes.create') }}"
                            data-url="{{ route('ajax.qrcodes.index') }}" id="print-qrcode-index">
                            {{ __('Qrcode Print') }}</li>
                        <li class="me-4" data-create-url="{{ route('ajax.itemCards.create') }}"
                            data-url="{{ route('ajax.itemCards.index') }}" id="item-cards-index">
                            {{ __('Items Card') }}</li>
                        <li class="me-4" data-type="opening-balance"
                            data-url="{{ route('ajax.openingBalances.index') }}" id="opening-balance">
                            {{ __('Opening Balance') }}</li>
                    </ul>
                </div>
            </div>
            <div class="row" style="margin-bottom: 10px;">
                <div class="col-8" id="main-content">
                    @yield('content')
                </div>
                <div class="col-4">
                    <div id="departments-section">
                        @include('components.department.index')
                    </div>
                </div>
            </div>

        </div>
        <!-- /.container-fluid -->



    </div>
    <!-- /.content-wrapper -->
    <!-- Sidebar -->
    {{--    @include('includes.nav') --}}
</div>
<!-- /#wrapper -->

@include('includes.footer')
