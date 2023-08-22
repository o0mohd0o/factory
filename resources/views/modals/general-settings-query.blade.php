<!-- Modal -->
<div class="modal fade fs-3" id="general-settings-query" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="general-settings-form" action="{{ route('ajax.gereralSettings.index') }}">
            @csrf

            <div class="modal-content">
                <div class="modal-header text-center">
                    <h5 class="modal-title" id="exampleModalLabel"> الاعدادات العامة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row ">
                        <div class="col-12 ">
                            <div class="form-group">
                                <label for="company_name">{{ __('Company Name') }}</label>
                                <input type="text" name="company_name" class="form-control fs-4"
                                    value="{{ $generalSettings ? $generalSettings->company_name : '' }}">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="company_address">{{ __('Company Address') }}</label>
                                <input type="text" name="company_address" class="form-control fs-4"
                                    value="{{ $generalSettings ? $generalSettings->company_address : '' }}">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="company_description">{{ __('Company Description') }}</label>
                                <textarea rows="4" name="company_description" class="form-control fs-4">{{ $generalSettings ? $generalSettings->company_description : '' }}</textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="company_phone">{{ __('Company Phone') }}</label>
                                <input type="textarea" name="company_phone" class="form-control fs-4"
                                    value="{{ $generalSettings ? $generalSettings->company_phone : '' }}">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <div class="form-check form-switch">
                                    <input type="hidden" name="reading_data_from_hesabat" value="off">
                                    <label class="form-check-label" for="flexSwitchCheckDefault">قراءة البيانات من
                                        برنامج حسابات</label>
                                    <input class="form-check-input" type="checkbox" role="switch"
                                        id="flexSwitchCheckDefault" name="reading_data_from_hesabat"
                                        @if ($generalSettings && $generalSettings->reading_data_from_hesabat) checked @endif>
                                </div>
                            </div>
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
        $('#general-settings-form').on('submit', function(e) {
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
