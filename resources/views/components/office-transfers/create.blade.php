<h1 class="text-center bg-white rounded py-1">{{ __('Office Transfers') }}</h1>


<div class="form-background">
    <h2 class="text-center bg-success text-white mb-2">{{ __('Create') }}</h2>
    <form id="office-transfer-form" action="{{ route('ajax.officeTransfers.store') }}" autocomplete="off" method="post">
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
                    <label for="type">{{ __('Transfer Type') }}</label>
                    <select class="form-select text-center" name="type" aria-label="Default select example">
                        <option value="to">{{ __('Transfer To Office') }}</option>
                        <option value="from">{{ __('Transfer From Office') }}</option>
                    </select>
                </div>
            </div>
        </div>
        <input autocomplete="false" name="hidden" type="text" style="display:none;">
        <table id="office-transfer-autocomplete-table"
            class="w-100 printForm create-form office-transfer-autocomplete-table">
            <thead>
                <tr>
                    <th>{{ __('Kind') }}</th>
                    <th>{{ __('Kind Name') }}</th>
                    <th>{{ __('Default Karat') }}</th>
                    <th>{{ __('Shares') }}</th>
                    <th>{{ __('Unit') }}</th>
                    <th>{{ __('QTY') }}</th>
                    <th>{{ __('Salary') }}</th>
                    <th>{{ __('Total Cost') }}</th>
                    <th class="table-borderless"></th>
                </tr>
            </thead>
            <tbody>
                <tr class="addrow">
                    <td><input type="text" id="kind-1" data-field-name="code"
                            class="form-control autocomplete_txt" autofill="off" autocomplete="off" name="kind[]"></td>
                    <td><input type="text" id="kind-name-1" data-field-name="name"
                            class="form-control autocomplete_txt" autofill="off" autocomplete="off" name="kind_name[]">
                    </td>
                    <td><input type="text" id="kind-karat-1" data-field-name="karat"
                            class="form-control autocomplete_txt" autofill="off" autocomplete="off" name="karat[]">
                    </td>
                    <td><input type="text" id="shares-1" data-field-name="shares" class="form-control "
                            autofill="off" name="shares[]">
                    </td>
                    <td>
                        <select class="form-control" name="unit[]" id="unit-1">
                            <option value="gram"> جرام</option>
                            <option value="kilogram">كيلو جرام</option>
                            <option value="ounce">أونصة </option>
                        </select>
                    </td>
                    <td><input type="text" class="form-control quantity" id="quantity-1" name="quantity[]"
                            value="1">
                    </td>
                    <td><input type="text" class="form-control salary" id="salary-1" name="salary[]" value="0">
                    </td>
                    <td><input type="text" class="form-control total-cost" id="total-cost-1" name="total_cost[]"
                            value="0" readonly></td>
                    <td><input type="text" class="form-control total-cost" id="total-cost-1" name="total_cost[]"
                            value="0" readonly></td>
                    <td class="table-borderless d-flex"> <a href="#" class="add-row m-1">
                            <i class="fas fa-plus-square fs-2" style="color: green;"></i>
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>


        <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
        <button type="button" id="undo" class="btn btn-danger">{{ __('Undo') }}</button>
        <button type="button" id="print_labels" class="btn btn-primary">
            {{ __('Print') }}
        </button>

    </form>
</div>

<script src="{{ asset('js/office-transfer.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#office-transfer-form').on('submit', function(e) {
            e.preventDefault();
            let url = $(this).attr('action');
            let data = new FormData(this);
            axios.post(url, data).then((response) => {
                toastr.success(response.data.message);
                axios.get("{{ route('ajax.officeTransfers.index') }}").then((
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
            axios.get("{{ route('ajax.officeTransfers.index') }}").then((
                response) => {
                $('#main-content').html(response.data);
            }).catch((error) => {
                toastr.error(error.response.data.message);
                axios.get(
                    "{{ route('ajax.officeTransfers.create') }}"
                ).then((
                    response) => {
                    $('#main-content').html(response.data);
                });
            });
        })
    });
</script>
