<h1 class="text-center bg-white rounded py-1">{{ __('Gold Transform') }}</h1>


<div class="form-background">
    <h2 class="text-center bg-success text-white mb-2">{{ __('Create') }}</h2>
    <form id="opening-balance-form" action="{{ route('ajax.openingBalances.store') }}" autocomplete="off" method="post">
        @csrf
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="value">{{ __('Document ID') }}</label>
                    <input type="text" value="{{ $lastId }}" class="form-control" readonly>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="value">{{ __('Document Date') }}</label>
                    <input type="text" value="<?php echo date('Y-m-d'); ?>" class="form-control" name="date" readonly>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="person_on_charge">{{ __('Person On Charge') }}</label>
                    <input value="{{ session('person_on_charge', '') }}" type="text" name="person_on_charge"
                        class="form-control">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="worker">{{ __('Worker') }}</label>
                    <input value="{{ session('Worker', '') }}" type="text" name="worker" class="form-control">
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

                            <th>{{ __('Item Current Balance') }}</th>

                            <th>{{ __('Used Weight') }}</th>

                            <th class="table-borderless"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="used-items-addrow" id="row-1">
                            <input type="hidden" name="used_item_id" id="used-item-id">

                            <td><input type="text" id="used-item-code"
                                    data-field-name="item" class="form-control autocomplete_txt" autofill="off"
                                    autocomplete="off" name="used_item"></td>
                            <td><input type="text" id="used-item-name"
                                    class="form-control autocomplete_txt" autofill="off" data-field-name="item_name"
                                    autocomplete="off" name="used_item_name"></td>

                            <td><input type="number" id="used-item-shares"
                                    class="form-control autocomplete_txt" autofill="off" data-field-name="shares"
                                    autocomplete="off" name="used_item_shares"></td>

                            <td><input type="number" id="used-item-weight-before-transform" class="form-control"
                                    name="used_item_weight_before_transform" readonly></td>
                            <td><input type="number" class="form-control weight-to-use" name="weight_to_use">
                            </td>
                            <td class="table-borderless">
                            </td>
                        </tr>
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
                            <th class="table-borderless"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="new-items-addrow" id="row-1">
                            <input type="hidden" name="new_item_id" id="new-item-id">

                            <td><input type="text" id="new-item-code"
                                    data-field-name="item" class="form-control autocomplete_txt" autofill="off"
                                    autocomplete="off" name="new_item"></td>
                            <td><input type="text" id="new-item-name"
                                    class="form-control autocomplete_txt" autofill="off" data-field-name="item_name"
                                    autocomplete="off" name="new_item_name"></td>

                            <td><input type="number" id="default-item-shares"
                                    class="form-control autocomplete_txt" autofill="off" 
                                    autocomplete="off" readonly></td>

                            <td><input type="number" id="new-item-shares"
                                    class="form-control autocomplete_txt" autofill="off" data-field-name="shares"
                                    autocomplete="off" name="new_item_shares"></td>

                            <td><input type="number" id="new-item-weight" class="form-control"
                                    name="new_item_weight" ></td>

                            <td><input type="number" class="form-control new-item-qty" name="new_item_qty"
                                    value="1">
                            <td><input type="number" class="form-control new-item-stone-weight" name="new_item_stone_weight">
                            </td>
                            <td class="table-borderless">
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>

            <div class="row mt-4">
                <div class="col-5 mx-auto">
                    <h3 class="text-center bg-warning text-dark mb-2 m-auto text-nowrap  w-25 border rounded">
                        {{ __('Gold Loss') }}</h3>

                    <table class="w-100 printForm create-form">
                        <thead>
                            <tr>
                                <th>{{ __('Calibrated In Gold 21') }}</th>
                                <th>{{ __('Calibrated In Gold 24') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>10</td>
                                <td>10</td>
                            </tr>
                        </tbody>
                    </table>


                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6 mt-4 text-center mx-auto">

                <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
                <button type="button" id="undo" class="btn btn-danger">{{ __('Undo') }}</button>
                <button type="button" id="print_labels" class="btn btn-primary">
                    {{ __('Print') }}
                </button>
                <button type="submit" name="submit"
                    class="btn btn-success mr-5">{{ __('Save And Transfer To Dept') }}</button>
                {{-- <label for="to_department" class="gold-transform-department-label">{{ __('To Department') }}</label> --}}
                <select class="form-select text-center gold-transform-department-select" name="department_id"
                    aria-label="Default select example">
                    <option value=""></option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>
</div>

<script src="{{ asset('js/opening-balance.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#opening-balance-form').on('submit', function(e) {
            e.preventDefault();
            let url = $(this).attr('action');
            let data = new FormData(this);
            axios.post(url, data).then((response) => {
                toastr.success(response.data.message);
                axios.get("{{ route('ajax.openingBalances.index') }}").then((
                    response) => {
                    $('#main-content').html(response.data);
                });
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
            axios.get("{{ route('ajax.openingBalances.index') }}").then((
                response) => {
                $('#main-content').html(response.data);
            }).catch((error) => {
                toastr.error(error.response.data.message);
                axios.get(
                    "{{ route('ajax.openingBalances.create') }}"
                ).then((
                    response) => {
                    $('#main-content').html(response.data);
                });
            });
        })
    });
</script>
