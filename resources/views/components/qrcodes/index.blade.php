<h1 class="text-center bg-white rounded py-1">{{ __('Print Qr Documents List') }}</h1>


<div class="row p-1" style="direction: rtl;">
    <div class="col-2">
        <button class="qrcode-navigator" data-id="{{ $qrcode->id }}" data-ordering="first"> <i
                class="fas fa-step-forward"></i> </button>
        <button class="qrcode-navigator" data-id="{{ $qrcode->id }}" data-ordering="previous"> <i
                class="fas fa-arrow-right"></i> </button>
    </div>


    <div class="col-8"></div>

    <div class="col-2" style="direction: ltr;">
        <button class="qrcode-navigator" data-id="{{ $qrcode->id }}" data-ordering="last"> <i
                class="fas fa-step-backward"></i> </button>
        <button class="qrcode-navigator" data-id="{{ $qrcode->id }}" data-ordering="next"> <i
                class="fas fa-arrow-left"></i> </button>
    </div>

</div>
<div class="form-background">
    <form id="qrcode-form" action="{{ route('ajax.qrcodes.store') }}" autocomplete="off" method="post">
        <div id="print-section">
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="value">{{ __('Document ID') }}</label>
                        <input type="text" value="{{ $qrcode->bond_num }}" class="form-control" readonly>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="value">{{ __('Document Date') }}</label>
                        <input type="text" value="{{ $qrcode->date }}" class="form-control" name="date" readonly>
                    </div>
                </div>
                {{-- <div class="col-2"></div> --}}
                <div class="col-sm-6">
                    <div class="form-group float-start">
                        <label for="person_on_charge">{{ __('Person On Charge') }}</label>
                        <input value="{{ $qrcode->person_on_charge }}" type="text" name="person_on_charge"
                            class="form-control">
                    </div>
                </div>


            </div>

            <input autocomplete="false" name="hidden" type="text" style="display:none;">
            <table id="qrcode-autocomplete-table"
                class="w-100 printForm create-form qrcode-autocomplete-table">
                <thead>
                    <tr>
                        {{-- <th>{{ __('ID') }}</th> --}}
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
                        <th class="no-print">{{ __('print') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($qrcode->details as $details)
                        <tr class="addrow" id="row_{{ $loop->iteration }}">
                            <td><input name="item_code[]" value="{{$details->item->code}}"  data-field-name="id" id="itemID_1" class="item-id form-control autocomplete_txt" autofill="off" autocomplete="off" readonly></td>
                            <td><input name="item_name[]" value="{{$details->item->name}}"  data-field-name="item_name" id="itemName_{{ $loop->iteration }}" class="itemName form-control autocomplete_txt" autofill="off" autocomplete="off" readonly></td>
                            <td><input name="serial[]" value="{{$details->serial}}"  data-field-name="serial" id="serial_{{ $loop->iteration }}" class="itemSerial form-control autocomplete_txt add_serial_items" autofill="off" autocomplete="off" readonly></td>
                            <td><input name="item_karat[]" value="{{$details->item->karat}}"  data-field-name="carat" id="carat_{{ $loop->iteration }}" class="carat form-control autocomplete_txt" autofill="off" autocomplete="off" readonly></td>
                            <td><input name="quantity[]" value="{{$details->quantity}}"  data-field-name="quantity" id="quantity_{{ $loop->iteration }}" class="weight form-control autocomplete_txt" autofill="off" autocomplete="off" readonly></td>
                            <td><input name="item_fare[]" value="{{$details->item->fare}}"  data-field-name="fare" id="fare_{{ $loop->iteration }}" class="fare form-control autocomplete_txt" autofill="off" autocomplete="off" readonly></td>
                            <td><input name="sales_price[]" value="{{$details->sales_price}}"  data-field-name="sales_price" id="salesPrice_1" class="sales-price form-control autocomplete_txt" autofill="off" autocomplete="off" readonly></td>
                            <td><input name="descrip1[]" value="{{$details->item->desc_1}}" data-field-name="descrip1" id="descrip1_{{ $loop->iteration }}" class="form-control autocomplete_txt" autofill="off" autocomplete="off" readonly></td>
                            <td><input name="descrip2[]" value="{{$details->item->desc_2}}"  data-field-name="descrip2" id="descrip2_{{ $loop->iteration }}" class="form-control autocomplete_txt" autofill="off" autocomplete="off" readonly></td>
                            <td><input name="descrip3[]" value="{{$details->item->desc_3}}"  data-field-name="descrip3" id="descrip3_{{ $loop->iteration }}" class="form-control autocomplete_txt" autofill="off" autocomplete="off" readonly></td>
                            <td><input name="descrip4[]" value="{{$details->item->desc_4}}"  data-field-name="descrip4" id="descrip4_{{ $loop->iteration }}" class="form-control autocomplete_txt" autofill="off" autocomplete="off" readonly></td>
                            <td><input name="descrip5[]" value="{{$details->item->desc_5}}"  data-field-name="descrip5" id="descrip5_{{ $loop->iteration }}"  class="form-control autocomplete_txt" autofill="off" autocomplete="off" readonly></td>
                            <td class="no-print"><input type="checkbox" name="print[]" data-field-name="print" id="print_{{ $loop->iteration }}" class="print_qr"></td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
            <div class="row form-inline ">
                <div class="col-sm-12 no-print">
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
                                <td>{{ $qrcode->count }}</td>
                                <td>{{ $qrcode->total_weight }}</td>
                                <td>{{ $qrcode->total_fare }}</td>
                                <td>{{ $qrcode->gold18 }}</td>
                                <td>{{ $qrcode->gold21 }}</td>
                                <td>{{ $qrcode->gold22 }}</td>
                                <td>{{ $qrcode->gold24 }}</td>
                                <td>{{ $qrcode->weight_all21 }}</td>
                                <td>{{ $qrcode->weight_all24 }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <button type="button" id="new-qrcode" data-href="{{ route('ajax.qrcodes.create') }}"
            class="btn btn-primary">
            {{ __('New') }}
        </button>
        <button type="button" id="edit-qrcode" data-href="{{ route('ajax.qrcodes.edit', $qrcode) }}"
            class="btn btn-success">
            {{ __('Edit') }}
        </button>
        <button type="button" id="delete-qrcode" data-href="{{ route('ajax.qrcodes.delete', $qrcode) }}"
            class="btn btn-danger">
            {{ __('Delete') }}
        </button>
        <button type="button" id="print_labels" class="btn btn-primary">
            {{ __('Print') }}
        </button>
        <button type="button" id="print-qr" class="btn btn-primary">{{ __('Print Document') }}</button>
    </form>
</div>
<script src="{{asset('js/print-qr.js')}}"></script>

<script>
    $(document).ready(function() {
        numbersArToEn();
        $('#new-qrcode').on('click', function(e) {
            e.preventDefault();
            let url = $(this).data('href');
            axios.get(url).then((response) => {
                $('#main-content').html(response.data);
            }).catch((error) => {
                toastr.error(error);
            })
        });

        $('.qrcode-navigator').on('click', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            let ordering = $(this).data('ordering');
            axios.get("{{ route('ajax.qrcodes.index') }}", {
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

        $('#edit-qrcode').on('click', function(e) {
            e.preventDefault();
            let url = $(this).data('href');
            axios.get(url).then((response) => {
                $('#main-content').html(response.data);
            }).catch((error) => {
                toastr.error(error);
            })
        });
        $('#delete-qrcode').on('click', function(e) {
            e.preventDefault();
            let url = $(this).data('href');
            axios.post(url).then((response) => {
                toastr.success(response.data.message);
                axios.get("{{ route('ajax.qrcodes.index') }}").then((
                    response) => {
                    $('#main-content').html(response.data);
                }).catch((error) => {
                    toastr.error(error.response.data.message);
                    axios.get(
                        "{{ route('ajax.qrcodes.create') }}"
                    ).then((
                        response) => {
                        $('#main-content').html(response.data);
                    });
                })
            }).catch((error) => {
                toastr.error(error);
            })
        });

    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        
        $('#print-qr').on('click', function () {
            $('#main-content').addClass('col-12').removeClass('col-8');
            $('#departments-section').css('display','none');
            $('.no-print').css('display','none');
            $('#print-section').addClass('section-to-print');
            window.print();
            $('#main-content').addClass('col-8').removeClass('col-12');
            $('#departments-section').css('display','block');
            $('.no-print').removeAttr('style');
            $('#print-section').removeClass('section-to-print');

        })
    });
</script>
