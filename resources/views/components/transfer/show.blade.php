<div class="container-fluid">
    <h1 class="text-center bg-white rounded py-1">{{$department->name}}</h1>

    <!-- Breadcrumbs-->
    <ol class="breadcrumb" style="padding:10px; justify-content: flex-end;">
        <li class="breadcrumb-item" style="direction: rtl;">{{ $department->name }}</li>
        <li class="breadcrumb-item" style="direction: rtl;">{{ __('Transfer Document') }}</li>
        <li class="breadcrumb-item">
            <a href="{{ route('home') }}">{{ __('Home') }}</a>
        </li>
    </ol>
    <div class="row" style="text-align: right;">
        <a href="{{ route('transfer.index') }}"><button class="btn-primary btn-sm transfer-index"
                style="direction: rtl">{{ __('Transfer Documents List') }}</button></a>
    </div>

    @if (session('message'))
        <div style="    padding: 0.75rem 1.25rem !important;"
            class="alert alert-{{ session('alert-type') }} alert-dismissible " role="alert" id="session-alert">
            {{ session('message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif


    <div class="form-background">
        <form id="order-form" action="{{ route('transfer.update', $transfer->id) }}" autocomplete="off"
            method="post">
            @csrf
            @method('PATCH')
            <div class="row">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="value">{{ __('Document ID') }}</label>
                        <input type="text" value="{{ $transfer->id }}" class="form-control" readonly>
                    </div>
                </div>
                <div class="col-sm-2" style="position: absolute;left: 50px;">
                    <div class="form-group">
                        <label for="value">{{ __('Person On Charge') }}</label>
                        <input type="text" value="{{ $transfer->person_on_charge }}" name="person_on_charge"
                            class="form-control" readonly>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="value">{{ __('Date') }}</label>
                        <input type="text" value="{{ $transfer->date }}" name="date" class="form-control"
                            readonly>
                    </div>
                </div>
            </div>
            <input autocomplete="false" name="hidden" type="text" style="display:none;">
            <table id="autocomplete_table" class="printForm create-form autocomplete_table">
                <thead>
                    <tr>
                        <th>{{ __('Material ID') }}</th>
                        <th>{{ __('Material Name') }}</th>
                        <th>{{ __('Item Previous Balance') }}</th>
                        <th>{{ __('Coming') }}</th>
                        <th>{{ __('Going') }}</th>
                        <th>{{ __('Certified Loss') }}</th>
                        <th>{{ __('Not Certified Loss') }}</th>
                        <th>{{ __('Other Loss') }}</th>
                        <th>{{ __('Add Stones') }}</th>
                        <th>{{ __('Add Copper') }}</th>
                        <th>{{ __('Add Other') }}</th>
                        <th>{{ __('Add Other') }}</th>
                        <th>{{ __('Add Other') }}</th>
                        <th>{{ __('Item Current Balance') }}</th>
                        <th>{{ __('Transfer From') }}</th>
                        <th>{{ __('Transfer To') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transfer->details as $transferDetails)
                        <tr class="addrow" id="row_{{ $loop->index }}">
                            <td><input name="item_id[]" value="{{ $transferDetails->item_id }}" data-field-name="id"
                                    id="itemID_{{ $loop->index }}" class="item-id form-control autocomplete_txt"
                                    autofill="off" autocomplete="off" readonly></td>
                            <td><input name="item_name[]" value="{{ $transferDetails->item_name }}"
                                    data-field-name="item_name" id="itemName_{{ $loop->index }}"
                                    class="itemName form-control autocomplete_txt" autofill="off" autocomplete="off"
                                    readonly></td>
                            <td><input name="previous_balance[]" value="{{ $transferDetails->previous_balance }}"
                                    data-field-name="previous_balance" id="previous_balance_{{ $loop->index }}"
                                    class="form-control" autofill="off" autocomplete="off" readonly></td>
                            <td><input name="coming[]" value="{{ $transferDetails->coming }}" data-field-name="coming"
                                    id="coming_{{ $loop->index }}" class="form-control" autofill="off"
                                    autocomplete="off" readonly></td>
                            <td><input name="going[]" value="{{ $transferDetails->going }}" data-field-name="going"
                                    id="going_{{ $loop->index }}" class="form-control" autofill="off"
                                    autocomplete="off" readonly></td>
                            <td><input name="certified_loss[]" value="{{ $transferDetails->certified_loss }}"
                                    data-field-name="certified_loss" id="certified_loss_{{ $loop->index }}"
                                    class="fare form-control" autofill="off" autocomplete="off" readonly></td>
                            <td><input name="not_certified_loss[]" value="{{ $transferDetails->not_certified_loss }}"
                                    data-field-name="not_certified_loss" id="not_certified_loss_{{ $loop->index }}"
                                    class="form-control" autofill="off" autocomplete="off" readonly></td>
                            <td><input name="other_loss[]" value="{{ $transferDetails->other_loss }}"
                                    data-field-name="other_loss" id="other_loss_{{ $loop->index }}"
                                    class="form-control" autofill="off" autocomplete="off" readonly></td>
                            <td><input name="add_stones[]" value="{{ $transferDetails->add_stones }}"
                                    data-field-name="add_stones" id="add_stones_{{ $loop->index }}"
                                    class="form-control" autofill="off" autocomplete="off" readonly></td>
                            <td><input name="add_copper[]" value="{{ $transferDetails->add_copper }}"
                                    data-field-name="add_copper" id="add_copper_{{ $loop->index }}"
                                    class="form-control" autofill="off" autocomplete="off" readonly></td>
                            <td><input name="add_other1[]" value="{{ $transferDetails->add_other1 }}"
                                    data-field-name="add_other1" id="add_other1_{{ $loop->index }}"
                                    class="form-control" autofill="off" autocomplete="off" readonly></td>
                            <td><input name="add_other2[]" value="{{ $transferDetails->add_other2 }}"
                                    data-field-name="add_other2" id="add_other2_{{ $loop->index }}"
                                    class="form-control" autofill="off" autocomplete="off" readonly></td>
                            <td><input name="add_other3[]" value="{{ $transferDetails->add_other3 }}"
                                    data-field-name="add_other3" id="add_other3_{{ $loop->index }}"
                                    class="form-control" autofill="off" autocomplete="off" readonly></td>
                            <td><input name="current_balance[]" value="{{ $transferDetails->current_balance }}"
                                    data-field-name="current_balance" id="current_balance_{{ $loop->index }}"
                                    class="form-control" autofill="off" autocomplete="off" readonly></td>
                            <td><input name="transfer_from_name[]" value="{{ $transferDetails->transfer_from_name }}"
                                    data-field-name="name" id="transferFrom_{{ $loop->index }}"
                                    class="form-control autocomplete_department" autofill="off" autocomplete="off"
                                    readonly>
                                <input type="hidden" name="transfer_from[]" value="{{ $transferDetails->transfer_from }}"
                                    data-field-name="transfer_from_hidden"
                                    id="transfer_from_hidden_{{ $loop->index }}" class="autocomplete_department">
                            </td>
                            <td><input name="transfer_to_name[]" value="{{ $transferDetails->transfer_to_name }}"
                                    data-field-name="name" id="transferTo_{{ $loop->index }}"
                                    class="form-control autocomplete_department" autofill="off" autocomplete="off"
                                    readonly>
                                <input type="hidden" name="transfer_to[]" value="{{ $transferDetails->transfer_to }}"
                                    data-field-name="transfer_from_hidden" id="transfer_to_hidden_{{ $loop->index }}"
                                    class="autocomplete_department">
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
            <button type="button" id="print_labels" class="btn btn-primary">
                {{ __('Print') }}
            </button>

        </form>
    </div>
</div>
<script src="{{ asset('js/transfer.js') }}"></script>

<script>
    $(document).ready(function() {
        $('.transfer-index').on('click', function(e) {
            e.preventDefault();
            console.log('{{ route('ajax.transfers.index', $department) }}');
            axios.get('{{ route("ajax.transfers.index", $department) }}').then((response) => {
                $('#main-content').html(response.data);
            }).catch((error) => {
                toastr.error(error);
            })
        });
    });
</script>
