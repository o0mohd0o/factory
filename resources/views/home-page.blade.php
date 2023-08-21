@include('includes.header')


<div id="wrapper" class="home-wrapper" style="background: url({{ asset('images/bg.jpg') }})">
    <div id="content-wrapper" >

        <div class="container-fluid">
            <div class="row">
                <div class="col-12" id="reports">
                    <ul>
                        <li class="me-4" data-type="item-card-settings" data-url="{{ route('ajax.itemCardSettings.show') }}"  id="item-card-settings"> {{__('Item Card Settings')}}</li>
                        <li class="me-4" data-type="department-karat-difference-report" data-bs-toggle="modal" data-bs-target="#department-report-karat-difference-query">{{__('Purity Differnce')}}</li>
                        <li class="me-4" data-type="department-daily-report-in-total" data-bs-toggle="modal" data-bs-target="#department-daily-report-in-total-query">{{__('Balance Summary')}}</li>
                        <li class="me-4" data-type="department-report" data-bs-toggle="modal" data-bs-target="#department-report-query">{{__("Depts Statements")}}</li>
                        <li class="me-4" data-type="department-daily-report" data-bs-toggle="modal" data-bs-target="#department-daily-report-query">{{__("Daily Journal")}}</li>
                        <li class="me-4" data-create-url="{{ route('ajax.qrcodes.create') }}" data-url="{{route('ajax.qrcodes.index')}}" id="print-qrcode-index"> {{__("Qrcode Print")}}</li>
                        <li class="me-4" data-create-url="{{ route('ajax.itemCards.create') }}" data-url="{{route('ajax.itemCards.index')}}" id="item-cards-index"> {{__("Items Card")}}</li>
                        <li class="me-4" data-type="general-settings" data-bs-toggle="modal" data-bs-target="#general-settings-query" id="general-settings"> {{__("General Settings")}}</li>
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
{{--    @include('includes.nav')--}}
</div>
<!-- /#wrapper -->

@include('includes.footer')
