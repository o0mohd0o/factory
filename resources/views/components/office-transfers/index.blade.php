<h1 class="text-center bg-white rounded py-1">{{ __('Office Transfers') }}</h1>
<h2 class="text-center bg-warning rounded py-1">{{ $departments->first()->name }}</h2>


<div class="row p-1" style="direction: rtl;">
    <div class="col-2">
        <button class="office-transfer-navigator" data-bond-num="{{ $officeTransfer->bond_num }}" data-ordering="first"> <i
                class="fas fa-step-forward"></i> </button>
        <button class="office-transfer-navigator" data-bond-num="{{ $officeTransfer->bond_num }}" data-ordering="previous"> <i
                class="fas fa-arrow-right"></i> </button>
    </div>


    <div class="col-8"></div>

    <div class="col-2" style="direction: ltr;">
        <button class="office-transfer-navigator" data-bond-num="{{ $officeTransfer->bond_num }}" data-ordering="last"> <i
                class="fas fa-step-backward"></i> </button>
        <button class="office-transfer-navigator" data-bond-num="{{ $officeTransfer->bond_num }}" data-ordering="next"> <i
                class="fas fa-arrow-left"></i> </button>
    </div>

</div>
<div class="form-background">
    <form id="office-transfer-form" action="{{ route('ajax.officeTransfers.store') }}" autocomplete="off"
        method="post">
        <div id="print-section" class="col-12">
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="value">{{ __('Document ID') }}</label>
                        <input type="text" value="{{ $officeTransfer->bond_num }}" class="form-control" readonly>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="value">{{ __('Document Date') }}</label>
                        <input type="text" value="{{ $officeTransfer->date }}" class="form-control" name="date"
                            readonly>
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
                        <select disabled class="form-select text-center" name="type" aria-label="Default select example">
                            <option value="to" {{$officeTransfer->type=='to'?"selected":""}}>{{__('Transfer To Office')}}</option>
                            <option value="from" {{$officeTransfer->type=='from'?"selected":""}}>{{__('Transfer From Office')}}</option>
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
                        <th>{{ __('Weight') }}</th>
                        <th>{{ __('Salary') }}</th>
                        <th>{{ __('Total Cost') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($officeTransfer->details as $details)
                        <tr>
                            <td> {{ $details->item->code }}</td>
                            <td> {{ $details->item->name }}</td>
                            <td> {{ $details->item->karat }}</td>
                            <td> {{ $details->actual_shares }}</td>
                            <td>
                                <select class="form-control" disabled>
                                    <option value="gram" {{ $details->unit == 'gram' ? 'Selected' : '' }}> جرام
                                    </option>
                                    <option value="kilogram" {{ $details->unit == 'kilogram' ? 'Selected' : '' }}>كيلو
                                        جرام
                                    </option>
                                    <option value="ounce" {{ $details->unit == 'ounce' ? 'Selected' : '' }}>أونصة
                                    </option>
                                </select>

                            </td>
                            <td> {{ $details->quantity }}</td>
                            <td> {{ $details->weight }}</td>
                            <td> {{ $details->salary }}</td>
                            <td>{{ $details->total_cost }} </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        <button type="button" id="new-office-transfer"
            data-href="{{ route('ajax.officeTransfers.create') }}" class="btn btn-primary">
            {{ __('New') }}
        </button>
        <button type="button" id="edit-office-transfer"
            data-href="{{ route('ajax.officeTransfers.edit', $officeTransfer) }}" class="btn btn-success">
            {{ __('Edit') }}
        </button>
        <button type="button" id="delete-office-transfer"
            data-href="{{ route('ajax.officeTransfers.delete', $officeTransfer) }}" class="btn btn-danger">
            {{ __('Delete') }}
        </button>
        <button type="button" id="print-open-balance" class="btn btn-primary">{{ __('Print') }}</button>
    </form>
</div>
<script src="{{ asset('js/office-transfer.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#new-office-transfer').on('click', function(e) {
            e.preventDefault();
            let url = $(this).data('href');
            axios.get(url).then((response) => {
                $('#main-content').html(response.data);
            }).catch((error) => {
                toastr.error(error);
            })
        });

        $('.office-transfer-navigator').on('click', function(e) {
            e.preventDefault();
            let bond_num = $(this).data('bond-num');
            let ordering = $(this).data('ordering');
            axios.get("{{ route('ajax.officeTransfers.index') }}", {
                params: {
                    bond_num: bond_num,
                    ordering: ordering,
                }
            }).then((response) => {
                $('#main-content').html(response.data);
            }).catch((error) => {
                toastr.error(error.response.data.message);
            })

        });

        $('#edit-office-transfer').on('click', function(e) {
            e.preventDefault();
            let url = $(this).data('href');
            axios.get(url).then((response) => {
                $('#main-content').html(response.data);
            }).catch((error) => {
                toastr.error(error);
            })
        });
        $('#delete-office-transfer').on('click', function(e) {
            e.preventDefault();
            let url = $(this).data('href');
            axios.post(url).then((response) => {
                toastr.success(response.data.message);
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
                })
            }).catch((error) => {
                let errors = error.response.data;
                if (error.response.status == 422) {
                    $.each(errors.errors, function(key, value) {
                        toastr.error( value);
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
        $('#print-open-balance').on('click', function() {
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
