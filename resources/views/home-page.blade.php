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
                                @if (auth()->user()->can('general_settings'))
                                    <li data-type="general-settings" data-bs-toggle="modal"
                                        data-bs-target="#general-settings-query" id="general-settings">
                                        {{ __('General Settings') }}</li>
                                @endif
                                @if (auth()->user()->can('item_card_settings'))
                                    <li data-type="item-card-settings"
                                        data-url="{{ route('ajax.itemCardSettings.show') }}" id="item-card-settings">
                                        {{ __('Item Card Settings') }}</li>
                                @endif

                            </ul>

                        </li>
                        <li class="dropdown" class="text-center" data-flip="false">
                            {{ __('Reports') }}
                            <ul class="dropdown-menu">
                                @if (auth()->user()->can('purity_differnce'))
                                    <li data-type="department-karat-difference-report" data-bs-toggle="modal"
                                        data-bs-target="#department-report-karat-difference-query">
                                        {{ __('Purity Differnce') }}</li>
                                @endif
                                @if (auth()->user()->can('balance_summary'))
                                    <li data-type="department-daily-report-in-total" data-bs-toggle="modal"
                                        data-bs-target="#department-daily-report-in-total-query">
                                        {{ __('Balance Summary') }}
                                    </li>
                                @endif
                                @if (auth()->user()->can('depts_statements'))
                                    <li data-type="department-report" data-bs-toggle="modal"
                                        data-bs-target="#department-report-query">{{ __('Depts Statements') }}</li>
                                @endif
                                @if (auth()->user()->can('daily_journal'))
                                    <li data-type="department-daily-report" data-bs-toggle="modal"
                                        data-bs-target="#department-daily-report-query">{{ __('Daily Journal') }}</li>
                                @endif

                            </ul>

                        </li>
                        @if (auth()->user()->can('qrcode_print'))
                            <li class="me-4" data-create-url="{{ route('ajax.qrcodes.create') }}"
                                data-url="{{ route('ajax.qrcodes.index') }}" id="print-qrcode-index">
                                {{ __('Qrcode Print') }}</li>
                        @endif

                        @if (auth()->user()->can('item_card_settings'))
                            <li class="me-4" data-create-url="{{ route('ajax.itemCards.create') }}"
                                data-url="{{ route('ajax.itemCards.index') }}" id="item-cards-index">
                                {{ __('Items Card') }}</li>
                        @endif
                        @if (auth()->user()->can('opening_balance'))
                        <li class="me-4" data-type="opening-balance"
                            data-url="{{ route('ajax.openingBalances.index') }}" data-create-url="{{route('ajax.openingBalances.create')}}" id="opening-balance">
                            {{ __('Opening Balance') }}</li>
                                                @endif

                            @if (auth()->user()->can('manage_users'))
                            <li class="me-4">
                                <a style="color: inherit;text-decoration: none;" href="{{ route('dashboard') }}" target="blank">
                                    {{ __('Manage Users') }}</a>
                            </li>
                        @endif
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
