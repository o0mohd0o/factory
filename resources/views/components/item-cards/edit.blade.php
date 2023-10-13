<div class="form-background">
    <h2 class="text-center bg-success text-white mb-2">{{ __('Edit') }}</h2>
    <form id="itemCard-update-form" action="{{ route('ajax.itemCards.update', $itemCard) }}" autocomplete="off" method="post">
        @csrf
        <div class="row">
            {{-- Hidden inputs --}}

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="value">{{ __('Material ID') }}</label>
                    <div class="input-group mb-3">
                        <input readonly type="text" class="form-control" id="basic-url"
                            value="{{ $itemCard->code }}" aria-describedby="basic-addon3">

                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="value">{{ __('Material Name') }}</label>
                    <input type="text" name="name" value="{{ $itemCard->name }}" class="form-control"
                    >
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label for="value">{{ __('Karat') }}</label>
                    <input type="text" name="karat" value="{{ $itemCard->karat }}" class="form-control"
                    >
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label for="shares">{{ __('Shares') }}</label>
                    <select class="form-select text-center" name="shares" aria-label="Default select example">
                        <option value="" @if ($itemCard->shares == null) selected @endif>  </option>
                        <option value="750" @if ($itemCard->shares == 750) selected @endif>750</option>
                        <option value="875" @if ($itemCard->shares == 875) selected @endif>875</option>
                        <option value="916.66" @if ($itemCard->shares == 916.66) selected @endif>916.66</option>
                        <option value="1000" @if ($itemCard->shares == 1000) selected @endif>1000</option>
                    </select>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="customer">{{ __('Fare') }}</label>
                    <input type="number" step="any" name="fare" value="{{ $itemCard->fare }}"
                        class="form-control">
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-floating">
                    <textarea name="desc_1" class="form-control" placeholder="اترك الوصف هنا" id="floatingTextarea">{{ $itemCard->desc_1 }}</textarea>
                    <label for="floatingTextarea">الوصف الأول</label>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-floating">
                    <textarea name="desc_2" class="form-control" placeholder="اترك الوصف هنا" id="floatingTextarea">{{ $itemCard->desc_2 }}</textarea>
                    <label for="floatingTextarea">الوصف الثانى</label>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-floating">
                    <textarea name="desc_3" class="form-control" placeholder="اترك الوصف هنا" id="floatingTextarea">{{ $itemCard->desc_3 }}</textarea>
                    <label for="floatingTextarea">الوصف الثالث</label>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-floating">
                    <textarea name="desc_4" class="form-control" placeholder="اترك الوصف هنا" id="floatingTextarea">{{ $itemCard->desc_4 }}</textarea>
                    <label for="floatingTextarea">الوصف الرابع</label>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-floating">
                    <textarea name="desc_5" class="form-control" placeholder="اترك الوصف هنا" id="floatingTextarea">{{ $itemCard->desc_5 }}</textarea>
                    <label for="floatingTextarea">الوصف الخامس</label>
                </div>
            </div>
        </div>


        <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
        <button type="button" id="undo" class="btn btn-danger">{{ __('Undo') }}</button>
        <button type="button" id="delete-itemCard" data-url="{{ route('ajax.itemCards.delete', $itemCard) }}"  class="btn btn-danger">
            {{ __('Delete') }}
        </button>
        <button type="button" id="print_labels" class="btn btn-primary">
            {{ __('Print') }}
        </button>

    </form>
</div>

<script>
    $(document).ready(function() {
        $('#itemCard-update-form').on('submit', function(e) {
            e.preventDefault();
            let url = $(this).attr('action');
            let data = new FormData(this);
            axios.post(url, data).then((response) => {
                toastr.success(response.data.message);
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
                        toastr.error( value);
                    });
                } else {
                    toastr.error(error.response.data.message);
                }
            });

        });

        $('#delete-itemCard').on('click', function(e) {
            e.preventDefault();
            let url = $(this).data('url');
            axios.post(url).then((response) => {
                toastr.success(response.data.message);
                axios.get('{{ route('ajax.itemCards.refreshCurrentLevelItems') }}', {
                    params: {
                        level_num: response.data.levelNum,
                        parent_id: response.data.parentId,
                    }
                }).then((response) => {
                    $("#items-index").html(response.data);
                })
            }).catch((error) => {
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

        $('#undo').on('click', function(e) {
            e.preventDefault();
            $('#item-card-content').html('');
        })
    });
</script>
