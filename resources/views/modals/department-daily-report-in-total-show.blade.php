<!-- Modal -->
<div class="modal fade" id="department-daily-report-in-total-show" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">

        <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="modal-title" id="exampleModalLabel">كشف أرصدة مجمع</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body section-to-print">
                <h2 class="text-center">
                    كشف أرصدة مجمع {{ __('Day') }} -
                    <span class="text-primary">{{ $day }}</span>
                </h2>


                <table class="table text-center">
                    <thead class="table-primary">
                        <tr>
                            <th>{{ __('Department Name') }}</th>
                            <th>{{ __('Previous Balance') }}</th>
                            <th>{{ __('Credit') }}</th>
                            <th>{{ __('Debit') }}</th>
                            <th>{{ __('Current Balance') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($departments as $department)
                            <tr>
                                <td class="fs-3">{{ $department->name }}</td>
                                <td class="fs-4">
                                    {{ $isSameDay ? $department->dailyReports->sum('previous_balance') : $department->dailyReports->sum('current_balance') }}
                                </td>
                                <td class="fs-4 text-success">
                                    {{ $isSameDay ? $department->dailyReports->sum('credit') : 0 }}</td>
                                <td class="fs-4 text-danger">
                                    {{ $isSameDay ? $department->dailyReports->sum('debit') : 0 }}</td>
                                <td class="fs-4 text-primary">
                                    {{ $department->dailyReports->sum('current_balance') }}
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">غلق</button>
                <button type="button" id="print" class="btn btn-primary">
                    {{ __('Print') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#department-daily-report-in-total-show").modal('show');
        $('#print').on('click', function (){
            window.print();
        })
    });
</script>
