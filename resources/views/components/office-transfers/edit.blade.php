<h1 class="text-center bg-white rounded py-1">{{ __('Office Transfers') }}</h1>
<h2 class="text-center bg-warning rounded py-1">{{ $departments->first()->name }}</h2>


<div class="form-background">
    <h2 class="text-center bg-success text-white mb-2">{{ __('Edit') }}</h2>
    <form id="office-transfer-form" action="{{ route('ajax.officeTransfers.update', $officeTransfer) }}" autocomplete="off"
        method="post">
        @csrf
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="value">{{ __('Document ID') }}</label>
                    <input type="text" value=" {{ $officeTransfer->bond_num }} " class="form-control" readonly>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="value">{{ __('Document Date') }}</label>
                    <input type="text" value="{{ $officeTransfer->date }}" class="form-control" name="date">
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="person_on_charge">{{ __('Person On Charge') }}</label>
                    <input value="{{ $officeTransfer->person_on_charge }}" type="text" name="person_on_charge"
                        class="form-control">
                </div>

            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="type">{{ __('Transfer Type') }}</label>
                    <select class="form-select text-center" name="type" aria-label="Default select example">
                        <option value="to" {{ $officeTransfer->type == 'to' ? 'selected' : '' }}>
                            {{ __('Transfer To Office') }} </option>
                        <option value="from" {{ $officeTransfer->type == 'from' ? 'selected' : '' }}>
                            {{ __('Transfer From Office') }} </option>
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
                @foreach ($officeTransfer->details as $key => $details)
                    <tr class="addrow">
                        <td><input type="text" id="kind-{{ $loop->index }}" data-field-name="code"
                                class="form-control autocomplete_txt" autofill="off" autocomplete="off" name="kind[]"
                                value="{{ $details->kind }}"></td>
                        <td><input type="text" id="kind-name-{{ $loop->index }}" data-field-name="name"
                                class="form-control autocomplete_txt" autofill="off" autocomplete="off"
                                name="kind_name[]" value="{{ $details->kind_name }}">
                        </td>
                        <td><input type="text" id="kind-karat-{{ $loop->index }}" data-field-name="karat"
                                class="form-control autocomplete_txt" autofill="off" autocomplete="off" name="karat[]"
                                value="{{ $details->karat }}">
                        </td>
                        <td><input type="text" id="shares-1" data-field-name="shares" class="form-control "
                                autofill="off" name="shares[]" value="{{ $details->shares }}">
                        </td>
                        <td>
                            <select class="form-control" name="unit[]" id="unit-{{ $loop->index }}">
                                <option value="gram" @if ($details->unit == 'gram') selected @endif> جرام</option>
                                <option value="kilogram" @if ($details->unit == 'kilogram') selected @endif>كيلو جرام
                                </option>
                                <option value="ounce" @if ($details->unit == 'ounce') selected @endif>أونصة </option>
                            </select>
                        </td>
                        <td><input type="text" class="form-control quantity" id="quantity-{{ $loop->index }}"
                                name="quantity[]" value="{{ $details->quantity }}">
                        </td>
                        <td><input type="text" class="form-control salary" id="salary-{{ $loop->index }}"
                                name="salary[]" value="{{ $details->salary }}"></td>
                        <td><input type="text" class="form-control total-cost" id="total-cost-{{ $loop->index }}"
                                name="total_cost[]" value="{{ $details->total_cost }}" readonly></td>
                        <td class="table-borderless d-flex"> <a href="#" class="add-row m-1">
                                <i class="fas fa-plus-square fs-2" style="color: green;"></i>
                                @if ($key > 0)
                                    <a href="#" class="remove-row m-1"><i
                                            class="fas fa-window-close text-danger fs-2"></i></a>
                                @endif
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
            });
        })
    });
</script>
