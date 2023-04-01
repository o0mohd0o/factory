<!DOCTYPE html>
<html lang="{{__('en')}}">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" type="image/jpg" href="favicon.jpg">

    <title>Factory</title>


    <!-- Custom fonts for this template-->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">

    <!-- Page level plugin CSS-->
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.css') }}" rel="stylesheet">

    <!-- Custom styles for this template-->
    {{-- <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet"> --}}
    <link href="{{ asset('css/sb-admin.css') }}" rel="stylesheet">


    <link href="{{ asset('css/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-treeview.min.css') }}" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/demoStyles.css') }}" />
    {{-- <link type="text/css" href="{{asset('assets/jquery-ui-1.8.4.custom/css/blitzer/jquery-ui-1.8.4.custom.css')}}" rel="stylesheet" /> --}}
    <link href="{{ asset('css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/buttons.dataTables.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-confirm-delete.css') }}">
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('hesabat/custom.js') }}" defer></script>
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script> --}}

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery-ui.css') }}" rel="stylesheet">
    <link href="{{ asset('hesabat/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    @stack('css')
    <script>
        var csrf_token = "{{ csrf_token() }}";

        function closeAll() {
            $('#main-content').children().css('display', 'none');
            $('#department-edit').remove();
        }
    </script>
    <script>
        var apiBasePath =
            "{{ Config('external.hesabat_base_url', 'https://tareklancer.com/projects/hesabat/public/') . 'api' }}";
    </script>
    @if ($generalSettings && $generalSettings->reading_data_from_hesabat)
        <script>
            var itemCardPath =
                "{{ Config('external.hesabat_base_url', 'https://tareklancer.com/projects/hesabat/public/') . 'api' . '/item-cards/fetch-items' }}";
        </script>
    @else
        <script>
            var itemCardPath = "{{ url('/') . '/fetch-items' }}";
        </script>
    @endif

</head>

<body id="page-top">
    <input type="hidden" value="<?= url('/') ?>" id="base_path" />




    <nav class="navbar navbar-expand navbar-dark bg-dark static-top" style="height: 56px;">

        <!-- Navbar -->

        <a class="color-white {{app()->getLocale() == 'ar' ? 'align-ar' : 'align-en'}}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
        <li class="nav-item dropdown" style="display: inline-block; position: absolute; left: 0;">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <span
                    class="flag-icon flag-icon-{{ Config::get('languages')[App::getLocale()]['flag-icon'] }}"></span>
                {{ Config::get('languages')[App::getLocale()]['display'] }}
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                @foreach (Config::get('languages') as $lang => $language)
                    @if ($lang != App::getLocale())
                        <a class="dropdown-item" href="{{ route('lang.switch', $lang) }}"><span
                                class="flag-icon flag-icon-{{ $language['flag-icon'] }}"></span>
                            {{ $language['display'] }}</a>
                    @endif
                @endforeach
            </div>
        </li>
    </nav>
    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui.js') }}"></script>
{{--    <script src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>--}}
    <script src="{{asset('vendor/bootstrap/js/bootstrap.js')}}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Page level plugin JavaScript-->
    <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('js/print/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/print/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('js/print/jszip.min.js') }}"></script>
    <script src="{{ asset('js/print/pdfmake.min.js') }}"></script>
    <script src="{{ asset('js/print/vfs_fonts.js') }}"></script>
    <script src="{{ asset('js/print/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('js/print/buttons.print.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/sb-admin.min.js') }}"></script>

    <!-- Demo scripts for this page-->
    <script src="{{ asset('js/demo/datatables-demo.js') }}"></script>
