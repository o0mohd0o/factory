
<h1 class="text-center bg-white rounded py-1">{{__("Departments")}}</h1>

<div style="direction: rtl;">
    @foreach ($departments as $department)
        <a href="{{ route('ajax.departments.edit', $department) }}" class="btn btn-primay department-btn fs-3"
            data-id="{{ $department->id }}"
            data-transfer-url={{route('ajax.transfers.index', $department)}}>{{ $department->name }}</a>
    @endforeach
</div>

<div class="text-center my-3">
    <a href="" class="btn btn-danger w-100 add-department fs-3">{{ __('Add Department') }}</a>
</div>

@include('components.department.create')
<div id="department-edit">

</div>


<script>
    $(document).ready(function() {
        numbersArToEn();
        //click add department
        $(".add-department").on("click", function(e) {
            e.preventDefault();
            $('#department-edit').remove();
            if ($("#department-create").css("display") == "none") {
                $("#department-create").css("display", "block");
            } else {
                $("#department-create").css("display", "none");
            }
        });


        $(".department-btn").on("click", function(e) {
            e.preventDefault();
            let url = $(this).data("transfer-url");
            let editUrl = $(this).attr("href");
            axios.get(url).then((response) => {
                $('#main-content').html(response.data);
            })
            axios.get(editUrl).then(function(response) {
                $('#department-edit').html(response.data);
            });
        });


    });
</script>
