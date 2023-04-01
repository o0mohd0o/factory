<!-- Modal -->
<div class="modal fade" id="department-report-karat-difference-query" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="department-report-karat-difference-form" action="{{ route('departments.karatDifferenceReports.show') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">فروق العيار</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="department" class="form-label">{{ __('Department Name') }}</label>
                        <select name="department_id" class="form-select">
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="form-group">
                            <label for="value">{{ __('From Date') }}</label>
                            <input type="text" name="from" class="form-control from-date-picker" data-date-format="yyyy-mm-dd"
                                value="{{ \Carbon\Carbon::yesterday()->format('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-group">
                            <label for="value">{{ __('To Date') }}</label>
                            <input type="text" name="to" class="form-control to-date-picker" data-date-format="yyyy-mm-dd"
                                value="{{ \Carbon\Carbon::today()->format('Y-m-d') }}">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">غلق</button>
                    <button type="submit" type="button" class="btn btn-primary">عرض</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        // $('.input-daterange').datepicker();
        // $('.input-daterange input').each(function() {
        //     $(this).datepicker('clearDates');
        // });
        $('.from-date-picker').datepicker({
            format: "YYYY-MM-DD",
        });
        $('.to-date-picker').datepicker({
            format: "YYYY-MM-DD",
        });

        $('#department-report-karat-difference-form').on('submit', function(e) {
            e.preventDefault();
            let data = new FormData(this);
            let url = $(this).attr('action');
            $('#department-report-karat-difference-query').modal('toggle');
            axios.post(url, data).then((response) => {
                $('#department-report-show-section').html(response.data);
                console.log(response.data);
            }).catch((error) => {
                toastr.error(error.response.data.message);
            })
        })
    });
</script>
