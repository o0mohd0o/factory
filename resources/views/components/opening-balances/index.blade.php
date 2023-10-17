<h1 class="text-center bg-white rounded py-1">{{ __('Opening Balance') }}</h1>


<div class="row p-1" style="direction: rtl;">
    <div class="col-2">
        <button class="opening-balance-navigator" data-id="{{ $openingBalance->id }}" data-ordering="first"> <i
                class="fas fa-step-forward"></i> </button>
        <button class="opening-balance-navigator" data-id="{{ $openingBalance->id }}" data-ordering="previous"> <i
                class="fas fa-arrow-right"></i> </button>
    </div>


    <div class="col-8"></div>

    <div class="col-2" style="direction: ltr;">
        <button class="opening-balance-navigator" data-id="{{ $openingBalance->id }}" data-ordering="last"> <i
                class="fas fa-step-backward"></i> </button>
        <button class="opening-balance-navigator" data-id="{{ $openingBalance->id }}" data-ordering="next"> <i
                class="fas fa-arrow-left"></i> </button>
    </div>

</div>
<div class="form-background">
    <form id="opening-balance-form" action="{{ route('ajax.openingBalances.store') }}" autocomplete="off"
        method="post">
        <div id="print-section" class="col-12">
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="value">{{ __('Document ID') }}</label>
                        <input type="text" value="{{ $openingBalance->bond_num }}" class="form-control" readonly>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="value">{{ __('Document Date') }}</label>
                        <input type="text" value="{{ $openingBalance->date }}" class="form-control" name="date"
                            readonly>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="value">{{ __('Inventory Record Number') }}</label>
                        <input type="text" value="{{ $openingBalance->inventory_record_num }}" class="form-control"
                            name="inventory_record_num" readonly>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="value">{{ __('Inventory Record Date') }}</label>
                        <input type="text" value="{{ $openingBalance->inventory_record_date }}" class="form-control"
                            name="inventory_record_date" readonly>
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
                        <select disabled class="form-select text-center" name="department_id" aria-label="Default select example">
                        @foreach ($departments as $department)
                            <option value="{{$department->id}}" {{$openingBalance->department_id==$department->id?"selected":""}}>{{$department->name}}</option>
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
                    </tr>
                </thead>
                <tbody>
                    @foreach ($openingBalance->details as $details)
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

        <button type="button" id="new-opening-balance"
            data-href="{{ route('ajax.openingBalances.create') }}" class="btn btn-primary">
            {{ __('New') }}
        </button>
        <button type="button" id="edit-opening-balance"
            data-href="{{ route('ajax.openingBalances.edit', $openingBalance) }}" class="btn btn-success">
            {{ __('Edit') }}
        </button>
        <button type="button" id="delete-opening-balance"
            data-href="{{ route('ajax.openingBalances.delete', $openingBalance) }}" class="btn btn-danger">
            {{ __('Delete') }}
        </button>
        <button type="button" id="print-open-balance" class="btn btn-primary">{{ __('Print') }}</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#new-opening-balance').on('click', function(e) {
            e.preventDefault();
            let url = $(this).data('href');
            axios.get(url).then((response) => {
                $('#main-content').html(response.data);
            }).catch((error) => {
                toastr.error(error);
            })
        });

        $('.opening-balance-navigator').on('click', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            let ordering = $(this).data('ordering');
            axios.get("{{ route('ajax.openingBalances.index') }}", {
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

        $('#edit-opening-balance').on('click', function(e) {
            e.preventDefault();
            let url = $(this).data('href');
            axios.get(url).then((response) => {
                $('#main-content').html(response.data);
            }).catch((error) => {
                toastr.error(error);
            })
        });
        $('#delete-opening-balance').on('click', function(e) {
            e.preventDefault();
            let url = $(this).data('href');
            axios.post(url).then((response) => {
                toastr.success(response.data.message);
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
