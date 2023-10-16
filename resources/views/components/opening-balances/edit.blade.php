<h1 class="text-center bg-white rounded py-1">{{ __('Opening Balance') }}</h1>


<div class="form-background">
    <h2 class="text-center bg-success text-white mb-2">{{ __('Edit') }}</h2>
    <form id="opening-balance-form" action="{{ route('ajax.openingBalances.update', $openingBalance) }}" autocomplete="off"
        method="post">
        @csrf
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="value">{{ __('Document ID') }}</label>
                    <input type="text" value=" {{ $newBondNum }} " class="form-control" readonly>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="value">{{ __('Document Date') }}</label>
                    <input type="text" value="{{ $openingBalance->date }}" class="form-control" name="date">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="value">{{ __('Inventory Record Number') }}</label>
                    <input type="text" value="{{ $openingBalance->inventory_record_num }}" class="form-control"
                        name="inventory_record_num">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="value">{{ __('Inventory Record Date') }}</label>
                    <input type="text" value="{{ $openingBalance->inventory_record_date }}" class="form-control"
                        name="inventory_record_date">
                </div>
            </div>

        </div>
        <div class="row">

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="person_on_charge">{{ __('Person On Charge') }}</label>
                    <input value="{{ $openingBalance->person_on_charge }}" type="text" name="person_on_charge"
                        class="form-control">
                </div>

            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="person_on_charge">{{ __('Department') }}</label>
                    <select class="form-select text-center" name="department_id" aria-label="Default select example">
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}"
                                {{ $openingBalance->department_id == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <input autocomplete="false" name="hidden" type="text" style="display:none;">
        <table id="opening-balance-autocomplete-table"
            class="w-100 printForm create-form opening-balance-autocomplete-table">
            <thead>
                <tr>
                    <th>{{ __('Kind') }}</th>
                    <th>{{ __('Kind Name') }}</th>
                    <th>{{ __('Default Karat') }}</th>
                    <th>{{ __('Shares') }}</th>
                    <th>{{ __('Unit') }}</th>
                    <th>{{ __('QTY') }}</th>
                    <th>{{ __('Weight') }}</th>
                    <th>{{ __('Salary') }}</th>
                    <th>{{ __('Total Cost') }}</th>
                    <th class="table-borderless"></th>

                </tr>
            </thead>
            <tbody>
                @foreach ($openingBalance->details as $key => $details)
            <tbody>
                <tr class="addrow">
                    <input type="hidden" name="item_id[]" value="{{ $details->item_id }}">
                    <td><input type="text" id="kind-1" data-field-name="code"
                            class="form-control autocomplete_txt" autofill="off" autocomplete="off" name="kind[]"
                            value="{{ $details->item->code }}"></td>
                    <td><input type="text" id="kind-name-1" data-field-name="name"
                            class="form-control autocomplete_txt" autofill="off" autocomplete="off" name="kind_name[]"
                            value="{{ $details->item->name }}">
                    </td>
                    <td><input type="text" id="kind-karat-1" data-field-name="karat"
                            class="form-control autocomplete_txt" autofill="off" autocomplete="off" name="karat[]"
                            value="{{ $details->item->karat }}">
                    </td>
                    <td><input type="text" id="shares-1" data-field-name="shares"
                            value="{{ $details->actual_shares }}" class="form-control " autofill="off"
                            name="actual_shares[]">
                    </td>
                    <td>
                        <select class="form-control unit" name="unit[]" id="unit-1">
                            <option value="gram" {{ $details->unit == 'gram' ? 'Selected' : '' }}> جرام
                            </option>
                            <option value="kilogram" {{ $details->unit == 'kilogram' ? 'Selected' : '' }}>كيلو
                                جرام
                            </option>
                            <option value="ounce" {{ $details->unit == 'ounce' ? 'Selected' : '' }}>أونصة
                            </option>
                        </select>

                    </td>
                    <td><input type="text" class="form-control quantity" id="quantity-1" name="quantity[]"
                            value="{{ $details->quantity }}" value="1">
                    </td>
                    <td><input type="text" class="form-control weight" id="weight-1" name="weight[]"
                            value="{{ $details->weight }}" value="1">
                    </td>
                    <td><input type="text" class="form-control salary" id="salary-1" name="salary[]"
                            value="{{ $details->salary }}">
                    </td>
                    <td><input type="text" class="form-control total-cost" id="total-cost-1" name="total_cost[]"
                            value="{{ $details->total_cost }} " value="0" readonly></td>
                    <td class="table-borderless d-flex"> <a href="#" class="add-row m-1">
                            <i class="fas fa-plus-square fs-2" style="color: green;"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>


        <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
        <button type="button" id="undo" class="btn btn-danger">{{ __('Undo') }}</button>
        <button type="button" id="print_labels" class="btn btn-primary">
            {{ __('Print') }}
        </button>

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
            });
        })
    });
</script>
