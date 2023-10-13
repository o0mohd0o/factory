<div class="form-background">
    <h2 class="text-center bg-success text-white mb-2">{{ __('Create') }}</h2>
    <form id="itemCard-form" action="{{ route('ajax.itemCards.store') }}" autocomplete="off" method="post">
        @csrf
        <div class="row">
            {{-- Hidden inputs --}}
            <input type="hidden" name="parent_id" value="{{ $parentId }}">
            <input type="hidden" name="level_num" value="{{ $levelNum }}">
            <input type="hidden" name="parent_code" value="{{ $parentItemCode }}">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="value">{{ __('Material ID') }}</label>
                    <div class="input-group mb-3">
                        <input name="sub_code" type="text" class="form-control" id="basic-url"
                            value="{{ $newItemCode }}">
                        @if ($parentItemCode)
                            <span class="input-group-text">{{ $parentItemCode }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="value">{{ __('Material Name') }}</label>
                    <input type="text" name="name" value="" class="form-control" id="box">
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label for="value">{{ __('Karat') }}</label>
                    <input type="text" name="karat" class="form-control">
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label for="karat">{{ __('Shares') }}</label>
                    <select class="form-select text-center" name="shares" aria-label="Default select example">
                        <option value="" selected></option>
                        <option value="750">750</option>
                        <option value="875">875</option>
                        <option value="916.66">916.66</option>
                        <option value="1000">1000</option>
                    </select>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="customer">{{ __('Fare') }}</label>
                    <input type="number" step="any" name="fare" value="" class="form-control"
                        id="box">
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-floating">
                    <textarea name="desc_1" class="form-control" placeholder="اترك الوصف هنا" id="floatingTextarea"></textarea>
                    <label for="floatingTextarea">الوصف الأول</label>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-floating">
                    <textarea name="desc_2" class="form-control" placeholder="اترك الوصف هنا" id="floatingTextarea"></textarea>
                    <label for="floatingTextarea">الوصف الثاني</label>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-floating">
                    <textarea name="desc_3" class="form-control" placeholder="اترك الوصف هنا" id="floatingTextarea"></textarea>
                    <label for="floatingTextarea">الوصف الثالث</label>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-floating">
                    <textarea name="desc_4" class="form-control" placeholder="اترك الوصف هنا" id="floatingTextarea"></textarea>
                    <label for="floatingTextarea">الوصف الرابع</label>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-floating">
                    <textarea name="desc_5" class="form-control" placeholder="اترك الوصف هنا" id="floatingTextarea"></textarea>
                    <label for="floatingTextarea">الوصف الخامس</label>
                </div>
            </div>

        </div>



        <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
        <button type="button" id="undo" class="btn btn-danger">{{ __('Undo') }}</button>


    </form>
</div>

<script>
    $(document).ready(function() {
        $('#itemCard-form').on('submit', function(e) {
            e.preventDefault();
            let url = $(this).attr('action');
            let data = new FormData(this);
            axios.post(url, data).then((response) => {
                toastr.success(response.data.message);
                $("#item-card-content").html('');
                axios.get('{{ route('ajax.itemCards.refreshCurrentLevelItems') }}', {
                    params: {
                        level_num: response.data.itemCard.level_num,
                        parent_id: response.data.itemCard.parent_id,
                    }
                }).then((response) => {
                    $("#items-index").html(response.data);
                })
            }).catch((error) => {
                let errors = error.response.data;
                if (error.response.status == 422) {
                    $.each(errors.errors, function(key, value) {
                        toastr.error(value);
                    });
                } else {
                    toastr.error(error.response.data.message);
                }
            });

        });

        $('#undo').on('click', function(e) {
            e.preventDefault();
            $("#item-card-content").html('');
        })
    });
</script>
