<h1 class="text-center bg-white rounded py-1">{{ __('Gold Transform') }}</h1>


<div class="form-background">
    <h2 class="text-center bg-success text-white mb-2">{{ __('Create') }}</h2>
    <form id="gold-transform-form" action="{{ route('ajax.goldTransforms.store') }}" autocomplete="off" method="post">
        @csrf
        <div class="row">
            <div class="col-sm-1">
                <div class="form-group">
                    <label for="value">{{ __('Document ID') }}</label>
                    <input type="text" name="bond_num" value="{{ $newBondNum }}" class="form-control" readonly>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label for="value">{{ __('Document Date') }}</label>
                    <input type="text" value="<?php echo date('Y-m-d'); ?>" class="form-control" name="date" readonly>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="department">{{ __('Department') }}</label>
                    <select class="form-select text-center" id="gold-transform-department" name="department_id"
                        aria-label="Default select example">
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="worker">{{ __('Worker') }}</label>
                    <select class="form-select text-center" id="gold-transform-worker" name="worker_id"
                        aria-label="Default select example">
                        <option value=""></option>
                        @foreach ($workers as $worker)
                            <option value="{{ $worker->id }}"> {{ $worker->name_ar }}</option>
                        @endforeach
                    </select>
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
                        <tr class="used-items-addrow">
                            <input type="hidden" class="item-id" name="used_item_id[]">

                            <td><input type="text" data-field-name="code"
                                    class="form-control used-items-autocomplete" autofill="off" autocomplete="off"
                                    name="used_item"></td>
                            <td><input type="text" class="form-control used-items-autocomplete" autofill="off"
                                    data-field-name="name" autocomplete="off" name="used_item_name"></td>

                            <td><input type="number" min="0" class="form-control used-items-autocomplete"
                                    autofill="off" data-field-name="shares" autocomplete="off" name="used_item_shares[]"
                                    readonly></td>

                            <td><input type="number" min="0" class="form-control"
                                    name="used_item_weight_before_transform" readonly></td>
                            <td><input type="number" min="0" step="any" class="form-control weight-to-use"
                                    name="weight_to_use[]" required>
                            </td>
                            <td class="table-borderless d-flex"> <a href="#" class="add-row m-1">
                                    <i class="fas fa-plus-square fs-2" style="color: green;"></i>
                                </a>
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
                        <tr class="new-items-addrow">
                            <input type="hidden" name="new_item_id[]">

                            <td><input type="text" data-field-name="code" class="form-control new-items-autocomplete"
                                    autofill="off" autocomplete="off" name="new_item" required></td>
                            <td><input type="text" class="form-control new-items-autocomplete" autofill="off"
                                    data-field-name="name" autocomplete="off" name="new_item_name" required></td>

                            <td><input type="number" min="0" class="form-control" autofill="off"
                                    name="new_item_karat" autocomplete="off" data-field-name="karat" readonly
                                    required></td>

                            <td><input type="number" min="" class="form-control" autofill="off"
                                    data-field-name="shares" autocomplete="off" name="new_item_shares[]" required
                                    required>
                            </td>

                            <td><input type="number" min="0" step="any" class="form-control"
                                    name="new_item_weight[]" required>
                            </td>

                            <td><input type="number" min="1" class="form-control new-item-qty"
                                    name="new_item_qty[]" value="1">
                            <td><input type="number" min="0" step="any"
                                    class="form-control new-item-stone-weight" name="new_item_stone_weight[]">
                            </td>
                            <td class="table-borderless d-flex"> <a href="#" class="add-row m-1">
                                    <i class="fas fa-plus-square fs-2" style="color: green;"></i>
                                </a>
                        </tr>
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

        <div class="row">
            <div class="col-6 mt-4 text-center mx-auto">

                <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
                <button type="button" id="undo" class="btn btn-danger">{{ __('Undo') }}</button>
                <button type="button" id="print_labels" class="btn btn-primary">
                    {{ __('Print') }}
                </button>
                <button type="submit" name="submit"
                    class="btn btn-success mr-5 save-and-transfer">{{ __('Save And Transfer To Dept') }}</button>
                {{-- <label for="to_department" class="gold-transform-department-label">{{ __('To Department') }}</label> --}}
                <select class="form-select text-center gold-transform-department-select"
                    name="transfer_to_department_id" aria-label="Default select example">
                    <option value=""></option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>
</div>

<script src="{{ asset('js/gold-transform.js') }}"></script>
<script>
    $(document).ready(function() {
        $(".new-items-autocomplete-table, .used-items-autocomplete-table")
            .on("click", "input", function(e) {
                $(this).select();
            });

        $('#gold-transform-form').on('submit', function(e) {
            e.preventDefault();
            let url = $(this).attr('action');
            let data = new FormData(this);
            axios.post(url, data).then((response) => {
                toastr.success(response.data.message);
                axios.get("{{ route('ajax.goldTransforms.index') }}").then((
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
            });
        })
    });
</script>
