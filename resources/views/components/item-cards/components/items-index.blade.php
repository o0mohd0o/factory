<div class="row">
    <div class="col-2"></div>
    <div class="col-8">
        <h2 class="text-center text-primary bg-white rounded py-1">{{ __('Level') . '' . $levelNum }}</h1>
            @if (isset($parentsItems))
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb fs-2 parent-items-nav">
                        @for ($i = count($parentsItems) - 1; $i > 0; $i--)
                            <li class="breadcrumb-item"><a class="next-items" data-query-type="next"
                                    data-level-num="{{ $parentsItems[$i]?->level_num + 1 }}"
                                    data-parent-id="{{ $parentsItems[$i]?->id }}" data-id="{{ $parentsItems[$i]?->id }}"
                                    href="#">{{ $parentsItems[$i]?->name }}</a></li>
                        @endfor
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ $parentsItems[0]?->name }}</li>
                    </ol>
                </nav>
            @endif
            <table class="table text-center fs-4" id="items-table">
                <thead>
                    <tr class="table-primary">
                        @if ($levelNum <= 5 && $levelNum > 1)
                            <th></th>
                        @endif
                        <th>{{ __('Material Sub ID') }}</th>
                        <th>{{ __('Material ID') }}</th>
                        <th>{{ __('Material Name') }}</th>
                        @if ($levelNum >= 1 && $levelNum < 5 && $items->count())
                            <th></th>
                        @endif

                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr class="{{ !$item->hasChilds() ? 'text-primary' : '' }}"
                            data-show-url={{ route('ajax.itemCards.edit', $item) }}
                            data-level-num="{{ $levelNum }}" data-parent-id="{{ $item->parent_id }}"
                            data-id="{{ $item->id }}">
                            @if ($levelNum <= 5 && $levelNum > 1)
                                <td>
                                    <a href="" data-query-type="previous" data-level-num="{{ $levelNum - 1 }}"
                                        data-parent-id="{{ $item->parent_id }}" data-id="{{ $item->id }}"
                                        class="previous-items"><i class="fas fa-chevron-circle-right"></i></a>
                                </td>
                            @endif
                            <td class="show-item-card">{{ $item->sub_code }}</td>
                            <td class="show-item-card">{{ $item->code }}</td>
                            <td class="show-item-card">{{ $item->name }}</td>
                            @if ($levelNum >= 1 && $levelNum < 5)
                                <td>
                                    {{-- @if (!$item->usedBefore()) --}}
                                    <a class="next-items" data-query-type="next" href=""
                                        data-level-num="{{ $levelNum + 1 }}" data-parent-id="{{ $item->id }}"
                                        data-id="{{ $item->id }}"><i class="fas fa-chevron-circle-left"></i></a>
                                    {{-- @endif --}}
                                </td>
                            @endif

                        </tr>

                    @empty
                        <tr>
                            @if ($levelNum <= 5 && $levelNum > 1)
                                <td>
                                    <a href="" data-query-type="previous" data-level-num="{{ $levelNum - 1 }}"
                                        data-parent-id="{{ $parentId }}" class="previous-items"><i
                                            class="fas fa-chevron-circle-right"></i></a>
                                </td>
                            @endif
                            <td colspan="3">{{ __('There is no items') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
    </div>
    <div class="col-2"></div>
</div>

<script>
    $(document).ready(function() {

        $("#items-table").on(
            "click",
            ".show-item-card",
            function() {
                let row = $(this).closest("tr");
                let url = row.data('show-url');
                axios.get(url).then((response) => {
                    $("#item-card-content").html(response.data);
                    $('.selected-item').removeClass('selected-item');
                    row.addClass('selected-item');
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
            }
        );

        $("#items-table, .parent-items-nav").on(
            "click",
            ".next-items, .previous-items",
            function(e) {
                e.preventDefault();
                let parentId = $(this).data('parent-id');
                let levelNum = $(this).data('level-num');
                let type = $(this).data('query-type');
                axios.get('{{ route('ajax.itemCards.getItemsPerParent') }}', {
                    params: {
                        level_num: levelNum,
                        parent_id: parentId,
                        type: type,
                    }
                }).then((response) => {
                    $("#main-content").html(response.data);
                }).catch((error) => {
                    let errors = error.response.data;
                    if (error.response.status == 422) {
                        $.each(errors.errors, function(key, value) {
                            toastr.error(value);
                        });
                    } else {
                        toastr.error(error.response.data.message);
                    }
                })
            });


    });
</script>
