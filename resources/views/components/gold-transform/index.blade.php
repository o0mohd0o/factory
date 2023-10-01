<h1 class="text-center bg-white rounded py-1">{{ __('Gold Transform') }}</h1>


<div class="row p-1" style="direction: rtl;">
    <div class="col-2">
        <button class="gold-transform-navigator" data-id="{{ $goldTransform->id }}" data-ordering="first"> <i
                class="fas fa-step-forward"></i> </button>
        <button class="gold-transform-navigator" data-id="{{ $goldTransform->id }}" data-ordering="previous"> <i
                class="fas fa-arrow-right"></i> </button>
    </div>


    <div class="col-8"></div>

    <div class="col-2" style="direction: ltr;">
        <button class="gold-transform-navigator" data-id="{{ $goldTransform->id }}" data-ordering="last"> <i
                class="fas fa-step-backward"></i> </button>
        <button class="gold-transform-navigator" data-id="{{ $goldTransform->id }}" data-ordering="next"> <i
                class="fas fa-arrow-left"></i> </button>
    </div>

</div>
<div class="form-background">
    <form id="gold-transform-form">
        <div class="row">
            <div class="col-sm-1">
                <div class="form-group">
                    <label for="value">{{ __('Document ID') }}</label>
                    <input type="text" value="{{ $goldTransform->id }}" class="form-control" readonly>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label for="value">{{ __('Document Date') }}</label>
                    <input type="text" value="{{$goldTransform->date}}" class="form-control" name="date" readonly>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="department">{{ __('Department') }}</label>
                    <select class="form-select text-center" id="gold-transform-department" name="department_id"
                        aria-label="Default select example">
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}" {{$goldTransform->department_id==$department->id?"selected":""}}>{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="person_on_charge">{{ __('Person On Charge') }}</label>
                    <input value="{{ $goldTransform->person_on_charge }}" type="text" name="person_on_charge"
                        class="form-control">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="worker">{{ __('Worker') }}</label>
                    <input value="{{ $goldTransform->worker }}" type="text" name="worker" class="form-control">
                </div>
            </div>

        </div>

        <div class="row mt-4">
            <div class="col-sm-6">
                <h3 class="text-center bg-warning text-dark mb-2 m-auto text-nowrap  w-25 border rounded">
                    {{ __('Used Items') }}</h3>

                <table id="used-items-autocomplete-table"
                    class="w-100 printForm create-form used-items-autocomplete-table">
                    <thead>
                        <tr>
                            <th>{{ __('Item Code') }}</th>

                            <th>{{ __('Item Name') }}</th>

                            <th>{{ __('Shares') }}</th>

                            <th>{{ __('Used Weight') }}</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($goldTransform->usedItems as $usedItem)
                            <tr>
                                <th>{{$usedItem->departmentItem->kind}}</th>

                                <th>{{$usedItem->departmentItem->kind_name}}</th>
    
                                <th>{{$usedItem->departmentItem->shares}}</th>
    
                                <th>{{$usedItem->weight }}</th>
    
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
            <div class="col-sm-6">
                <h3 class="text-center bg-warning text-dark mb-2 m-auto text-nowrap  w-25 border rounded">
                    {{ __('New Items') }}</h3>

                <table id="new-items-autocomplete-table"
                    class="w-100 printForm create-form new-items-autocomplete-table">
                    <thead class="text-nowrap">
                        <tr>
                            <th>{{ __('Item Code') }}</th>
                            <th>{{ __('Item Name') }}</th>
                            <th>{{ __('Default Karat') }}</th>
                            <th>{{ __('Shares') }}</th>
                            <th>{{ __('Weight') }}</th>
                            <th>{{ __('QTY') }}</th>
                            <th>{{ __('Stone Weight') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($goldTransform->newItems as $newItem)
                            <tr>
                                <td>{{ $newItem->item->code }} </td>
                                <td>{{ $newItem->item->name }} </td>
                                <td>{{ $newItem->item->karat }} </td>
                                <td>{{ $newItem->actual_shares }} </td>
                                <td>{{ $newItem->weight }} </td>
                                <td>{{ $newItem->quantity }} </td>
                                <td>{{ $newItem->stone_weight }} </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

            <div class="row mt-4">
                <div class="col-5 mx-auto">
                    <h3 class="text-center bg-warning text-dark mb-2 m-auto text-nowrap  w-25 border rounded">
                        {{ __('Gold Loss In Gram') }}</h3>

                    <table id="gold-transform-loss" class="w-100 printForm create-form">
                        <thead>
                            <tr>
                                <th>{{ __('Calibrated In Gold 21') }}</th>
                                <th>{{ __('Calibrated In Gold 24') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="loss-calib-in-21">0</td>
                                <td class="loss-calib-in-24">0</td>
                            </tr>
                        </tbody>
                    </table>


                </div>
            </div>
        </div>


        <button type="button" id="new-gold-transform" data-href="{{ route('ajax.goldTransforms.create') }}"
            class="btn btn-primary">
            {{ __('New') }}
        </button>
        <button type="button" id="edit-gold-transform"
            data-href="{{ route('ajax.goldTransforms.edit', $goldTransform) }}" class="btn btn-success">
            {{ __('Edit') }}
        </button>
        <button type="button" id="delete-gold-transform"
            data-href="{{ route('ajax.goldTransforms.delete', $goldTransform) }}" class="btn btn-danger">
            {{ __('Delete') }}
        </button>
        <button type="button" id="print-gold-transform" class="btn btn-primary">{{ __('Print') }}</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#new-gold-transform').on('click', function(e) {
            e.preventDefault();
            let url = $(this).data('href');
            axios.get(url).then((response) => {
                $('#main-content').html(response.data);
            }).catch((error) => {
                toastr.error(error);
            })
        });

        $('.gold-transform-navigator').on('click', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            let ordering = $(this).data('ordering');
            axios.get("{{ route('ajax.goldTransforms.index') }}", {
                params: {
                    id: id,
                    ordering: ordering,
                }
            }).then((response) => {
                $('#main-content').html(response.data);
            }).catch((error) => {
                toastr.error(error.response.data.message);
            })

        });

        $('#edit-gold-transform').on('click', function(e) {
            e.preventDefault();
            let url = $(this).data('href');
            axios.get(url).then((response) => {
                $('#main-content').html(response.data);
            }).catch((error) => {
                toastr.error(error);
            })
        });
        $('#delete-gold-transform').on('click', function(e) {
            e.preventDefault();
            let url = $(this).data('href');
            axios.post(url).then((response) => {
                toastr.success(response.data.message);
                axios.get("{{ route('ajax.goldTransforms.index') }}").then((
                    response) => {
                    $('#main-content').html(response.data);
                }).catch((error) => {
                    toastr.error(error.response.data.message);
                    axios.get(
                        "{{ route('ajax.goldTransforms.create') }}"
                    ).then((
                        response) => {
                        $('#main-content').html(response.data);
                    });
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
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#print-gold-transform').on('click', function() {
            $('#main-content').addClass('col-12').removeClass('col-8');
            $('#departments-section').css('display', 'none');
            $('.no-print').css('display', 'none');
            $('#print-section').addClass('section-to-print');
            window.print();
            $('#main-content').addClass('col-8').removeClass('col-12');
            $('#departments-section').css('display', 'block');
            $('.no-print').removeAttr('style');
            $('#print-section').removeClass('section-to-print');

        })
    });
</script>
