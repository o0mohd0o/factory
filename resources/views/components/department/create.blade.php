<div id="department-create" style="display: none;">

    <ol class="breadcrumb" style="padding:10px; justify-content: flex-end;">
        <li class="breadcrumb-item ">{{ __('Create Department') }}</li>
    </ol>
    @if (session('message'))
        <div style="    padding: 0.75rem 1.25rem !important;"
            class="alert alert-{{ session('alert-type') }} alert-dismissible " role="alert" id="session-alert">
            {{ session('message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div style="background-color: #e9ecef;padding: 40px; border-radius: 0.25rem; margin-top: 20px">
        <form id="department-create-form" action="{{ route('ajax.departments.store') }}" method="post">
            @csrf
            <div class="row">

                <div class="col-12">
                    <div class="form-group">
                        <label for="value">{{ __('Department Name') }}</label>
                        <input type="text" name="name" class="form-control" id="box">
                    </div>
                </div>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        //Save the department
        $("#department-create-form").on("submit", function(e) {
            e.preventDefault();
            let data = new FormData(this);
            let url = $(this).attr("action");

            axios
                .post(url, data)
                .then(function(result) {
                    let data = result.data;
                    if (data.status == "success") {
                        toastr.success(data.message);
                        axios.get('{{ route('ajax.departments.index') }}').then((response) => {
                            $("#departments-section").html(response.data);
                        });

                    } else {
                        toastr.error(data.message);
                    }
                })
                .catch(function(error) {
                    let errors = error.response.data;
                    if (error.response.status == 422) {
                        $.each(errors.errors, function(key, value) {
                            toastr.error( value);
                        });
                    } else {
                        toastr.error(error.response.data.message);
                    }
                });
        });

    });
</script>
