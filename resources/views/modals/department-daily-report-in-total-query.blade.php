<!-- Modal -->
<div class="modal fade" id="department-daily-report-in-total-query" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="department-daily-report-in-total-form" action="{{ route('departments.dailyReportsInTotal.show') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">كشف حساب مجمع</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <div class="form-group">
                            <label for="value">{{ __('Date') }}</label>
                            <input type="text" name="day" class="form-control day-date-picker" data-date-format="yyyy-mm-dd"
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

        $('.day-date-picker').datepicker({
        });

        $('#department-daily-report-in-total-form').on('submit', function(e) {
            e.preventDefault();
            let data = new FormData(this);
            let url = $(this).attr('action');
            $('#department-daily-report-in-total-query').modal('toggle');
            axios.post(url, data).then((response) => {
                $('#report-show-section').html(response.data);
                console.log(response.data);
            }).catch((error) => {
                toastr.error(error.response.data.message);
            })
        })
    });
</script>
