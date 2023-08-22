<h1 class="text-center bg-white rounded py-1">{{ __('Items List') }}</h1>



<div class="container-fluid  form-background" style="direction: rtl;">
    <div id="items-index">
        @include('components.item-cards.components.items-index', [
            'items' => $items,
            'levelNum' => $levelNum,
        ])
    </div>


    <div class="row">
        <div class="col-3"></div>
        <div class="col-6 text-center">
            <button type="button" id="new-itemCard" data-href="{{ route('ajax.itemCards.create') }}"
                class="btn btn-primary fs-4">
                {{ __('Create Item') }}
            </button>
        </div>
        <div class="col-3"></div>

    </div>

    <div class="row" id="item-card-content">

    </div>
</div>


<script>
    $(document).ready(function() {

        $('#new-itemCard').on('click', function(e) {
            e.preventDefault();
            axios.get('{{ route('ajax.itemCards.create') }}', {
                params: {
                    level_num: '{{ $levelNum }}',
                    parent_id: '{{ $parentId }}',
                }
            }).then((response) => {
                $("#item-card-content").html(response.data);
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

        })
    });
</script>
