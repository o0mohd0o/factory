<h1 class="text-center bg-white rounded py-1">{{ __('Print Qr Documents List') }}</h1>


<div class="form-background">
    <h2 class="text-center bg-success text-white mb-2">{{ __('Edit') }}</h2>
    <form id="qrcode-form" action="{{ route('ajax.qrcodes.update', $qrcode) }}" autocomplete="off" method="post">
        @csrf
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="value">{{ __('Document ID') }}</label>
                    <input type="text" value="{{ $qrcode->id }}" class="form-control" readonly>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="value">{{ __('Document Date') }}</label>
                    <input type="text" value="<?php echo date('Y-m-d'); ?>" class="form-control" name="date" readonly>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group float-start">
                    <label for="person_on_charge">{{ __('Person On Charge') }}</label>
                    <input value="{{ $qrcode->person_on_charge }}" type="text" name="person_on_charge"
                        class="form-control">
                </div>
            </div>
        </div>
        <input autocomplete="false" name="hidden" type="text" style="display:none;">
        <table id="qrcode-autocomplete-table" class="w-100 printForm create-form qrcode-autocomplete-table">
            <thead>
                <tr>
                    <th>{{ __('Material ID') }}</th>
                    <th>{{ __('Material Name') }}</th>
                    <th>{{ __('serial') }}</th>
                    <th>{{ __('Carat') }}</th>
                    <th>{{ __('Weight') }}</th>
                    <th>{{ __('Fare') }}</th>
                    <th>{{ __('Sales Price') }}</th>
                    <th>{{ __('Description 1') }}</th>
                    <th>{{ __('Description 2') }}</th>
                    <th>{{ __('Description 3') }}</th>
                    <th>{{ __('Description 4') }}</th>
                    <th>{{ __('Description 5') }}</th>
                    <th>{{__('print')}}</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($qrcode->details as $details)
                    <tr class="addrow" id="row_{{ $loop->iteration }}">
                        <td>
                            <input class="item-id form-control autocomplete_txt" type="hidden" name="item_id[]"
                                value="{{ $details->item->id }}" id="itemID_{{ $loop->iteration }}">
                            <input name="item_code[]" data-field-name="code" id="itemCode_{{ $loop->iteration }}"
                                value="{{ $details->item->code }}" class="item-code form-control autocomplete_txt"
                                autofill="off" autocomplete="off">
                        </td>
                        <td><input name="item_name[]" data-field-name="name" id="itemName_{{ $loop->iteration }}"
                                value="{{ $details->item->name }}" class="itemName form-control autocomplete_txt"
                                autofill="off" autocomplete="off"></td>
                        <td><input name="serial[]" data-field-name="serial" id="serial_{{ $loop->iteration }}"
                                value="{{ $details->serial }}" class="itemSerial form-control add_serial_items"
                                autofill="off" autocomplete="off"></td>
                        <td><input name="karat[]" data-field-name="karat" id="carat_{{ $loop->iteration }}" class="carat form-control"
                                value="{{ $details->item->karat }}" autofill="off" autocomplete="off"></td>
                        <td><input name="quantity[]" value="{{ $details->quantity }}" data-field-name="quantity"
                                id="quantity_{{ $loop->iteration }}" class="weight form-control autocomplete_txt" autofill="off"
                                autocomplete="off"></td>
                        <td><input name="fare[]" value="{{ $details->item->fare }}" data-field-name="fare"
                                id="fare_{{ $loop->iteration }}" class="fare form-control autocomplete_txt" autofill="off"
                                autocomplete="off"></td>
                        <td><input name="sales_price[]" value="{{ $details->sales_price }}"
                                data-field-name="sales_price" id="salesPrice_{{ $loop->iteration }}"
                                class="sales-price form-control autocomplete_txt" autofill="off" autocomplete="off">
                        </td>
                        <td><input name="desc_1[]" value="{{ $details->item->desc_1 }}"
                                data-field-name="descrip1" id="descrip1_{{ $loop->iteration }}" class="form-control autocomplete_txt"
                                autofill="off" autocomplete="off">
                        </td>
                        <td><input name="desc_2[]" value="{{ $details->item->desc_2 }}"
                                data-field-name="descrip2" id="descrip2_{{ $loop->iteration }}" class="form-control autocomplete_txt"
                                autofill="off" autocomplete="off">
                        </td>
                        <td><input name="desc_3[]" value="{{ $details->item->desc_3 }}"
                                data-field-name="descrip3" id="descrip3_{{ $loop->iteration }}" class="form-control autocomplete_txt"
                                autofill="off" autocomplete="off"></td>
                        <td><input name="desc_4[]" value="{{ $details->item->desc_4 }}"
                                data-field-name="descrip4" id="descrip4_{{ $loop->iteration }}" class="form-control autocomplete_txt"
                                autofill="off" autocomplete="off"></td>
                        <td><input name="desc_5[]" value="{{ $details->item->desc_5 }}"
                                data-field-name="descrip5" id="descrip5_{{ $loop->iteration }}" class="form-control autocomplete_txt"
                                autofill="off" autocomplete="off"></td>
                        <td><input type="checkbox" name="print[]" data-field-name="print" id="print_{{ $loop->iteration }}" class="print_qr"></td>
                        {{-- <td class="table-borderless">
                            <a href="http://" class="remove-row"><i
                                    class="fas fa-window-close text-danger fs-3"></i></a>
                        </td> --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="row form-inline ">
            <div class="col-sm-12">
                <div class="form-group float-start">
                    <label for="">{{__('Print All')}}</label>
                    <input id="printAll" class="form-check-input mt-0" type="checkbox" value=""
                           aria-label="Checkbox for following text input">

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <table class="create-form" style="width: 50%">
                    <thead>
                        <tr>
                            <th>{{ __('Count') }}</th>
                            <th>{{ __('Total Weight') }}</th>
                            <th>{{ __('Total Fare') }}</th>
                            <th>{{ __('Gold 18') }}</th>
                            <th>{{ __('Gold 21') }}</th>
                            <th>{{ __('Gold 22') }}</th>
                            <th>{{ __('Gold 24') }}</th>
                            <th>{{ __('Weight Carat 21') }}</th>
                            <th>{{ __('Weight Carat 24') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input value="{{ $qrcode->count }}" name="count" class="form-control"
                                    autofill="off" autocomplete="off" readonly></td>
                            <td><input value="{{ $qrcode->total_weight }}" name="total_weight"
                                    class="form-control" autofill="off" autocomplete="off" readonly></td>
                            <td><input value="{{ $qrcode->total_fare }}" name="total_fare" class="form-control"
                                    id="item" autofill="off" autocomplete="off" readonly></td>
                            <td><input value="{{ $qrcode->gold18 }}" name="gold18" class="form-control"
                                    autofill="off" autocomplete="off" readonly></td>
                            <td><input value="{{ $qrcode->gold21 }}" name="gold21" class="form-control"
                                    autofill="off" autocomplete="off" readonly></td>
                            <td><input value="{{ $qrcode->gold22 }}" name="gold22" class="form-control"
                                    autofill="off" autocomplete="off" readonly></td>
                            <td><input value="{{ $qrcode->gold24 }}" name="gold24" class="form-control"
                                    autofill="off" autocomplete="off" readonly></td>
                            <td><input value="{{ $qrcode->weight_all21 }}" name="weight_in21" class="form-control"
                                    autofill="off" autocomplete="off" readonly></td>
                            <td><input value="{{ $qrcode->weight_all24 }}" name="weight_in24" class="form-control"
                                    autofill="off" autocomplete="off" readonly></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>


        <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
        <button type="button" id="undo" class="btn btn-danger">{{ __('Undo') }}</button>
        <button type="button" id="print_labels" class="btn btn-primary">
            {{ __('Print') }}
        </button>

    </form>
</div>

<script src="{{ asset('js/print-qr.js') }}"></script>
<script>
    $(document).ready(function() {
        
        $('#qrcode-form').on('submit', function(e) {
            e.preventDefault();
            let url = $(this).attr('action');
            let data = new FormData(this);
            axios.post(url, data).then((response) => {
                toastr.success(response.data.message);
                axios.get("{{ route('ajax.qrcodes.index') }}").then((
                    response) => {
                    $('#main-content').html(response.data);
                });
            }).catch((error) => {
                let errors = error.response.data;
                if (errors.status == 422) {
                    $.each(errors.errors, function(key, value) {
                        toastr.error(key + ":" + errors.message);
                    });
                } else {
                    toastr.error(error.response.data.message);
                }
            });

        });

        $('#undo').on('click', function(e) {
            e.preventDefault();
            axios.get("{{ route('ajax.qrcodes.index') }}").then((
                response) => {
                $('#main-content').html(response.data);
            });
        })
    });
</script>