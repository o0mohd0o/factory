{{-- Delete Modal --}}
<div class="modal fade" id="DeleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{__('Delete Department')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h4>{{__('Confirm to Delete Department')}}</h4>
                <input type="hidden" id="deleteing_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Cancel')}}</button>
                @if (!$department->main_department)
                    <a href="{{ route('ajax.departments.delete', $department) }}"
                       class="btn btn-danger delete_department">{{__('Yes Delete')}}</a>
                @endif
            </div>
        </div>
    </div>
</div>
<div style="direction: rtl;">
    <a href="http://" class="text-white close-department-edit" style="font-size: 25px;"><i
            class="fas fa-window-close"></i>{{ __('Close') }}</a>
</div>
<ol class="breadcrumb" style="padding:10px; justify-content: flex-end;">
    <li class="breadcrumb-item">{{ __('Update Department') }}</li>
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
    <form id="department-edit-form" action="{{ route('ajax.departments.update') }}" method="post">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label for="value">{{ __('Department ID') }}</label>
                    <input name="id" type="text" class="form-control" id="box" value="{{ $department->id }}"
                        readonly>
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label for="value">{{ __('Department Name') }}</label>
                    <input type="text" name="name" value="{{ $department->name }}" class="form-control" id="box">
                </div>
            </div>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
        @if (!$department->main_department)
            <a href="{{ route('ajax.departments.delete', $department) }}"
                class="btn btn-danger delete-department">{{ __('Delete') }}</a>
        @endif
    </form>
</div>

<script>
    $(document).ready(function() {
        // Translation
        let deletePhrase, yesDeletePhrase;
        if($('html').attr('lang') == 'ar') {
            deletePhrase = 'جاري الحذف..';
            yesDeletePhrase = 'تأكيد الحذف';
        } else {
            deletePhrase = 'Deleting..';
            yesDeletePhrase = 'Yes Delete';
        }
        //Update the department
        $("#department-edit-form").on("submit", function(e) {
            e.preventDefault();
            let data = new FormData(this);
            let url = $(this).attr("action");
            console.log(data + url);
            axios
                .post(url, data)
                .then(function(result) {
                    let data = result.data;
                    if (data.status == "success") {
                        toastr.success(data.message);
                        $('#department-edit').remove();

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
                            toastr.error(key + ":" + errors.message);
                        });
                    } else {
                        toastr.error(error.response.data.message);
                    }
                });
        });

        $(document).on('click', '.delete-department', function (e) {
            e.preventDefault();
            // $('#dept-id-model').html(" " + deptName + " <span class='ltr'> " +  " " + deptID + " </span> " );
            $('#DeleteModal').modal('show');
        });

        $('.delete_department').on("click", function(e) {
            e.preventDefault();
            $(this).text(deletePhrase);
            let url = $(this).attr('href');
            axios.post(url, {
                _token: csrf_token,
            }).then(function(response) {
                if (response.data.status == 'success') {
                    $(this).text(yesDeletePhrase);
                    $('#DeleteModal').modal('hide');
                    toastr.success(response.data.message);
                    axios.get('{{ route('ajax.departments.index') }}').then((response) => {
                        $("#departments-section").html(response.data);
                    });
                }
            }).catch((error) => {
                let errors = error.response.data;
                if (errors.status == 422) {
                    $.each(errors.errors, function(key, value) {
                        toastr.error(key + ":" + errors.message);
                    });
                } else {
                    toastr.error(error.response.data.message);
                }
            });
            numbersArToEn();
        });

        $('.close-department-edit').on('click', function(e) {
            e.preventDefault();
            $('#department-edit').html('');

        });
    });
</script>
