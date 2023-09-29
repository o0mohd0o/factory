<h1 class="text-center bg-white rounded py-1">{{ __('Opening Balance') }}</h1>


<div class="row p-1" style="direction: rtl;">
    <div class="col-2">
        <button class="gold-transform-navigator" data-id="{{ $goldTransform->id }}" data-ordering="first"> <i
                class="fas fa-step-forward"></i> </button>
        <button class="gold-transform-navigator" data-id="{{ $goldTransform->id }}" data-ordering="previous"> <i
                class="fas fa-arrow-right"></i> </button>
    </div>


    <div class="col-8"></div>

    <div class="col-2" style="direction: ltr;">
        <button class="gold-transform-navigator" data-id="{{ $goldTransform->id }}" data-ordering="last"> <i
                class="fas fa-step-backward"></i> </button>
        <button class="gold-transform-navigator" data-id="{{ $goldTransform->id }}" data-ordering="next"> <i
                class="fas fa-arrow-left"></i> </button>
    </div>

</div>
<div class="form-background">
    <form id="gold-transform-form" action="{{ route('ajax.goldTransforms.store') }}" autocomplete="off"
        method="post">
        <div id="print-section" class="col-12">
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="value">{{ __('Document ID') }}</label>
                        <input type="text" value="{{ $goldTransform->id }}" class="form-control" readonly>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="value">{{ __('Document Date') }}</label>
                        <input type="text" value="{{ $goldTransform->date }}" class="form-control" name="date"
                            readonly>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="value">{{ __('Inventory Record Number') }}</label>
                        <input type="text" value="{{ $goldTransform->inventory_record_num }}" class="form-control"
                            name="inventory_record_num" readonly>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="value">{{ __('Inventory Record Date') }}</label>
                        <input type="text" value="{{ $goldTransform->inventory_record_date }}" class="form-control"
                            name="inventory_record_date" readonly>
                    </div>
                </div>

            </div>
            <div class="row">

                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="person_on_charge">{{ __('Person On Charge') }}</label>
                        <input value="{{ $goldTransform->person_on_charge }}" type="text" name="person_on_charge"
                            class="form-control">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="person_on_charge">{{ __('Department') }}</label>
                        <select disabled class="form-select text-center" name="department_id" aria-label="Default select example">
                        @foreach ($departments as $department)
                            <option value="{{$department->id}}" {{$goldTransform->department_id==$department->id?"selected":""}}>{{$department->name}}</option>
                        @endforeach
                        </select>   
                    </div>
                </div>
            </div>
            <input autocomplete="false" name="hidden" type="text" style="display:none;">
            <table id="gold-transform-autocomplete-table"
                class="w-100 printForm create-form gold-transform-autocomplete-table">
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
                    </tr>
                </thead>
                <tbody>
                    @foreach ($goldTransform->details as $details)
                        <tr>
                            <td> {{ $details->kind }}</td>
                            <td> {{ $details->kind_name }}</td>
                            <td> {{ $details->karat }}</td>
                            <td> {{ $details->shares }}</td>
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
                            <td> {{ $details->salary }}</td>
                            <td>{{ $details->total_cost }} </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        <button type="button" id="new-gold-transform"
            data-href="{{ route('ajax.goldTransforms.create') }}" class="btn btn-primary">
            {{ __('New') }}
        </button>
        <button type="button" id="edit-gold-transform"
            data-href="{{ route('ajax.goldTransforms.edit', $goldTransform) }}" class="btn btn-success">
            {{ __('Edit') }}
        </button>
        <button type="button" id="delete-gold-transform"
            data-href="{{ route('ajax.goldTransforms.delete', $goldTransform) }}" class="btn btn-danger">
            {{ __('Delete') }}
        </button>
        <button type="button" id="print-gold-transform" class="btn btn-primary">{{ __('Print') }}</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#new-gold-transform').on('click', function(e) {
            e.preventDefault();
            let url = $(this).data('href');
            axios.get(url).then((response) => {
                $('#main-content').html(response.data);
            }).catch((error) => {
                toastr.error(error);
            })
        });

        $('.gold-transform-navigator').on('click', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            let ordering = $(this).data('ordering');
            axios.get("{{ route('ajax.goldTransforms.index') }}", {
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

        $('#edit-gold-transform').on('click', function(e) {
            e.preventDefault();
            let url = $(this).data('href');
            axios.get(url).then((response) => {
                $('#main-content').html(response.data);
            }).catch((error) => {
                toastr.error(error);
            })
        });
        $('#delete-gold-transform').on('click', function(e) {
            e.preventDefault();
            let url = $(this).data('href');
            axios.post(url).then((response) => {
                toastr.success(response.data.message);
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
        $('#print-gold-transform').on('click', function() {
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
