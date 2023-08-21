<ul class="sidebar navbar-nav">
    <li class="nav-item active">
        <a class="nav-link" href="{{url('/')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>{{__('Home')}}</span>
        </a>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="itemsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-fw fa-folder"></i>
            <span>{{__('Items Card')}}</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="pagesDropdown">
            <a class="dropdown-item" href="{{route ('items.index')}}">
                <i class="fas fa-fw fa-folder"></i>
                <span>{{__('Items List')}}</span>
            </a>
            <a class="dropdown-item" href="{{route ('items.create')}}">
                <i class="fas fa-fw fa-folder"></i>
                <span>{{__('Add Item')}}</span>
            </a>
            <a  id="item-card-a" class="dropdown-item" href="{{route("items.cardItem")}}">
                <i class="fas fa-fw fa-folder"></i>
                <span>{{__('Item Card')}}</span>
            </a>
        </div>

    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="deptDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-fw fa-folder"></i>
            <span>{{__('Departments')}}</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="pagesDropdown">
            <a class="dropdown-item" href="{{route ('depart.index')}}">
                <i class="fas fa-fw fa-folder"></i>
                <span>{{__('Departments List')}}</span>
            </a>
            <a class="dropdown-item" href="{{route ('depart.create')}}">
                <i class="fas fa-fw fa-folder"></i>
                <span>{{__('Add Department')}}</span>
            </a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="qrcodeDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-fw fa-folder"></i>
            <span>{{__('QR Code Print')}}</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="pagesDropdown">
            <a class="dropdown-item" href="{{route ('qrcode.index')}}">
                <i class="fas fa-fw fa-folder"></i>
                <span>{{__('Print Qr Documents List')}}</span>
            </a>
            <a class="dropdown-item" href="{{route ('qrcode.create')}}">
                <i class="fas fa-fw fa-folder"></i>
                <span>{{__('Add QR Code')}}</span>
            </a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="deptDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-fw fa-folder"></i>
            <span>{{__('Transfer')}}</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="pagesDropdown">
            <a class="dropdown-item" href="{{route ('transfer.index')}}">
                <i class="fas fa-fw fa-folder"></i>
                <span>{{__('Transfer Documents List')}}</span>
            </a>
            <a class="dropdown-item" href="{{route ('transfer.create')}}">
                <i class="fas fa-fw fa-folder"></i>
                <span>{{__('Create Transfer')}}</span>
            </a>
        </div>
    </li>

{{--    <li class="nav-item dropdown">--}}
{{--        <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
{{--            <i class="fas fa-fw fa-folder"></i>--}}
{{--            <span>Reports</span>--}}
{{--        </a>--}}
{{--        <div class="dropdown-menu" aria-labelledby="pagesDropdown">--}}
{{--            <a class="dropdown-item" href="workers_production.php">Workers Production</a>--}}
{{--            <a class="dropdown-item" href="report_material_balance.php">Materials Movement</a>--}}
{{--        </div>--}}

{{--    </li>--}}
</ul>
