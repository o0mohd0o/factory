<!-- Modal -->
<div class="modal fade" id="department-daily-report-show" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">

        <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="modal-title" id="exampleModalLabel"> الحركة اليومية</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body section-to-print">
                <h2 class="text-center">
                    الحركة اليومية {{ __('Day') }} -
                    <span class="text-primary">{{ $day }}</span>
                </h2>

                @foreach ($departments as $department)
                    <h2 class=" text-center p-2 m-2  text-danger border border-danger rounded">
                        {{ $department->name }}
                    </h2>

                    @if ($department->dailyReports->count())
                        <table class="table text-center">
                            <thead class="table-primary">
                                <tr>
                                    <th>{{ __('Kind') }}</th>
                                    <th>{{ __('Kind Name') }}</th>
                                    <th>{{ __('Default Karat') }}</th>
                                    <th>{{ __('Shares') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Item Previous Balance') }}</th>
                                    <th>{{ __('Credit') }}</th>
                                    <th>{{ __('Debit') }}</th>
                                    <th>{{ __('Item Current Balance') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($department->dailyReports as $report)
                                    <tr>
                                        <td>{{ $report->kind }}</td>
                                        <td>{{ $report->kind_name }}</td>
                                        <td>{{ $report->karat }}</td>
                                        <td class="text-danger fs-5">{{ $report->shares }}</td>
                                        <td>
                                            {{ $day }}
                                        </td>
                                        <td>
                                            {{ $day==$report->date?$report->previous_balance:$report->current_balance }}
                                        </td>
                                        <td>{{ $day==$report->date?$report->credit:0 }}</td>
                                        <td>{{ $day==$report->date?$report->debit:0 }}</td>
                                        <td>
                                            {{ $report->current_balance }}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="5"></td>

                                    <td class="table-info text-danger fs-5">
                                        {{ $day==$report->date?$department->dailyReports->sum('previous_balance'):$department->dailyReports->sum('current_balance') }}
                                    </td>
                                    <td class="table-info text-danger fs-5">
                                        {{ $day==$report->date?$department->dailyReports->sum('credit'):0 }}</td>
                                    <td class="table-info text-danger fs-5">
                                        {{ $day==$report->date?$department->dailyReports->sum('debit'):0 }}</td>
                                    <td class="table-info text-danger fs-5">
                                        {{ $department->dailyReports->sum('current_balance') }}
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    @else
                        <p class="text-center text-info fs-2">
                            {{ __('Sorry,There is no any items in this day or transfers for this department :name', ['name' => $department->name]) }}
                        </p>
                    @endif
                @endforeach

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">غلق</button>
                <button type="button" id="print" class="btn btn-primary">{{ __('Print') }}</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#department-daily-report-show").modal('show');
        $('#print').on('click', function (){
            window.print();
        })
    });
</script>
