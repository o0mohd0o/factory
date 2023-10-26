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
                                    {{ roundAndFormat($departmentsOpeningBalances->where('department_id')->first()?->balance, 2, 3) }}
                                </td>
                                <td class="fs-4 text-success">
                                    {{ roundAndFormat($departmentsTotalDayBalance->where('department_id', $department->id)->first()?->total_credit, 2, 3) }}
                                </td>
                                <td class="fs-4 text-danger">
                                    {{ roundAndFormat($departmentsTotalDayBalance->where('department_id', $department->id)->first()?->total_debit, 2, 3) }}
                                </td>
                                <td class="fs-4 text-primary">
                                    {{ roundAndFormat(
                                        $departmentsOpeningBalances->where('department_id', $department->id)->first()?->balance +
                                            $departmentsTotalDayBalance->where('department_id', $department->id)->first()?->total_debit -
                                            $departmentsTotalDayBalance->where('department_id', $department->id)->first()?->total_credit,
                                        2,
                                        3,
                                    ) }}
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="fs-3"></td>
                            <td class="fs-3">
                                {{ roundAndFormat($departmentsOpeningBalances->sum('balance'), 2, 3) }}
                            </td>
                            <td class="fs-3 text-success">
                                {{ roundAndFormat($departmentsTotalDayBalance->sum('total_credit'), 2, 3) }}
                            </td>
                            <td class="fs-3 text-danger">
                                {{ roundAndFormat($departmentsTotalDayBalance->sum('total_debit'), 2, 3) }}
                            </td>
                            <td class="fs-3 text-primary">
                                {{ roundAndFormat(
                                    $departmentsOpeningBalances->sum('balance') +
                                        $departmentsTotalDayBalance->sum('total_debit') -
                                        $departmentsTotalDayBalance->sum('total_credit'),
                                    2,
                                    3,
                                ) }}
                            </td>
                        </tr>
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
        $('#print').on('click', function() {
            window.print();
        })
    });
</script>
