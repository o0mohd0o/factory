<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>
{{-- <script src="{{asset('js/disable-atuofill.js')}}"></script> --}}
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    toastr.options.positionClass = "toast-top-center";
</script>
<script src="{{asset('js/header-buttons.js')}}"></script>
<link href="{{ asset('css/bootstrap-datepicker.css') }}" rel="stylesheet">
<script src="{{ asset('js/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
@stack('js')

@include('includes.modals')

<div id="report-show-section" style="direction: rtl;">

</div>

<script type="text/javascript">
    function numbersArToEn(){
        let input = $('input');
        input.on('keyup', function(e) {
           input.each(function(){
            let arabic = ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];
            let english = ['0','1','2','3','4','5','6','7','8','9'];
            for (let i = 0; i < arabic.length; i++)
            {
                this.value = this.value.replace(`${arabic[i]}`, `${english[i]}`);
            }
            });
        });
    }
</script>
</body>

</html>
