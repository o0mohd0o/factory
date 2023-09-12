<!-- Modal -->
<div class="modal fade fs-3" id="edit-userRole" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="edit-userRole-form" action="{{ route('ajax.manageUsers.update') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h5 class="modal-title" id="exampleModalLabel"> {{ __("Manage Users") }} </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        @foreach ($roles as $role)
                        <div class="col-6">
                            <div class="form-group form-check check-roles">
                                <input class="form-check-input " type="checkbox" name="{{ $role->name }}" id="{{ $role->name }}">
                                <label for="{{ $role->name }}" class="form-check-label mr-32">  {{ roleName($role->name) }}</label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <input type="hidden" id="user_id" name="user_id" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ __("Close") }}</button>
                    <button type="submit" type="button" class="btn btn-primary">{{ __("Save") }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        {{--  $("#item-card-settings-show").modal('show');  --}}

        $('#edit-userRole-form').on('submit', function(e) {
            e.preventDefault();
            let url = $(this).attr('action');
            let data = new FormData(this);
            axios.post(url, data).then((response) => {
                toastr.success(response.data.message);
                $("#edit-userRole").modal('hide');
            }).catch((error) => {
                let errors = error.response.data;
                if (error.response.status == 422) {
                    $.each(errors.errors, function(key, value) {
                        toastr.error( value);
                    });
                } else {
                    toastr.error(error.response.data.message);
                }
            })
        });
    });

</script>
