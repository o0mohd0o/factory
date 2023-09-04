<!doctype html>
<html lang="en" @if(App::isLocale('ar')) dir="rtl" @endif>

<head>

    <meta charset="utf-8">
    <title>Dashboard | Veltrix - Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description">
    <meta content="Themesbrand" name="author">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('dash/assets/images/favicon.ico') }}">

    <link href="{{ asset('dash/assets/libs/chartist/chartist.min.css') }}" rel="stylesheet">

    <!-- Bootstrap Css -->
    @if(App::isLocale('ar'))
    <link href="{{ asset('dash/assets/css/bootstrap-rtl.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@100;200;300;400;500;600&display=swap" rel="stylesheet">
    @else
    <link href="{{ asset('dash/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css">
    @endif

    <!-- DataTables -->
    <link href="{{ asset('dash/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('dash/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">

    <!-- Responsive datatable examples -->
    <link href="{{ asset('dash/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">

    <!-- Icons Css -->
    <link href="{{ asset('dash/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">
    <!-- App Css-->
    @if(App::isLocale('ar'))
    <link href="{{ asset('dash/assets/css/app-rtl.min.css') }}" id="app-style" rel="stylesheet" type="text/css">
    @else
    <link href="{{ asset('dash/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css">
    @endif
    <link href="{{ asset('dash/assets/css/custom.css') }}" id="app-style" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

</head>

<body data-sidebar="dark">
    <div id="layout-wrapper">
        @include("dash-layouts.header")
        @include("dash-layouts.verticalmenu")
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid" id="pageContent">

                </div>
            </div>
        </div>
    </div>
    <!-- JAVASCRIPT -->
    <script src="{{ asset('dash/assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('dash/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dash/assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('dash/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('dash/assets/libs/node-waves/waves.min.js') }}"></script>

    <!-- Required datatable js -->
    <script src="{{ asset('dash/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dash/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Buttons examples -->
    <script src="{{ asset('dash/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('dash/assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('dash/assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('dash/assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('dash/assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ asset('dash/assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('dash/assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('dash/assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
    <!-- Responsive examples -->
    <script src="{{ asset('dash/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('dash/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.5.0/axios.min.js" integrity="sha512-aoTNnqZcT8B4AmeCFmiSnDlc4Nj/KPaZyB5G7JnOnUEkdNpCZs1LCankiYi01sLTyWy+m2P+W4XM+BuQ3Q4/Dg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Datatable init js -->
    <script src="{{ asset('dash/assets/js/pages/datatables.init.js') }}"></script>

    <script src="{{ asset('dash/assets/js/app.js') }}"></script>

    <script src="{{ asset('js/header-buttons.js') }}"></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        toastr.options.positionClass = "toast-top-center";

    </script>

    <script>
        $(document).ready(function() {
            $("#logout").click(function(e) {
                e.preventDefault();
                let url = "{!! route('logout') !!}";
                let data = {};
                axios.post(url, data).then((response) => {
                    window.location.reload();
                }).catch((error) => {
                    let errors = error.response.data;
                    if (errors.status == 422) {
                        $.each(errors.errors, function(key, value) {
                            toastr.error(key + ":" + errors.message);
                        });
                    } else {
                        toastr.error(error.response.data.message);
                    }
                })
            });
        });

    </script>

</body>

</html>
