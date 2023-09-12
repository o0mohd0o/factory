<!-- Modal -->
<div class="modal fade fs-3" id="item-card-settings-show" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="item-card-settings-form" action="{{ route('ajax.itemCardSettings.update', $itemCardSettings) }}">
            @csrf

            <div class="modal-content">
                <div class="modal-header text-center">
                    <h5 class="modal-title" id="exampleModalLabel"> اعدادات كارت الصنف</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row ">
                        <div class="col-12 ">
                            <div class="form-group">
                                <label for="company_name">{{ __('Level One') }}</label>
                                <input type="text" name="level_1" class="form-control fs-4"
                                    value="{{ $itemCardSettings->level_1 }}">
                            </div>
                        </div>
                        <div class="col-12 ">
                            <div class="form-group">
                                <label for="company_name">{{ __('Level Two') }}</label>
                                <input type="text" name="level_2" class="form-control fs-4"
                                    value="{{ $itemCardSettings->level_2 }}">
                            </div>
                        </div>
                        <div class="col-12 ">
                            <div class="form-group">
                                <label for="company_name">{{ __('Level Three') }}</label>
                                <input type="text" name="level_3" class="form-control fs-4"
                                    value="{{ $itemCardSettings->level_3 }}">
                            </div>
                        </div>
                        <div class="col-12 ">
                            <div class="form-group">
                                <label for="company_name">{{ __('Level Four') }}</label>
                                <input type="text" name="level_4" class="form-control fs-4"
                                    value="{{ $itemCardSettings->level_4 }}">
                            </div>
                        </div>
                        <div class="col-12 ">
                            <div class="form-group">
                                <label for="company_name">{{ __('Level Five') }}</label>
                                <input type="text" name="level_5" class="form-control fs-4"
                                    value="{{ $itemCardSettings->level_5 }}">
                            </div>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">غلق</button>
                    <button type="submit" type="button" class="btn btn-primary">حفظ</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>

    $(document).ready(function() {
        $("#item-card-settings-show").modal('show');

        $('#item-card-settings-form').on('submit', function(e) {
            e.preventDefault();
            let url = $(this).attr('action');
            let data = new FormData(this);
            axios.post(url, data).then((response) => {
                toastr.success(response.data.message);
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
