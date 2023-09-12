<h2 class=" text-center p-2 m-2  text-danger border border-danger rounded">
    {{ __('Office Transfers') }}
</h2>

@forelse ($officeTransfersReports as $day => $reports)
    <h3 class="transfer-day text-center  m-2  text-info border border-info rounded">
        {{ __('Day') . '  ' . $day }}
    </h3>

    <table class="table text-center">
        <thead class="table-primary">
            <tr>
                <th>{{ __('Kind') }}</th>
                <th>{{ __('Kind Name') }}</th>
                <th>{{ __('Credit') }}</th>
                <th>{{ __('Debit') }}</th>
                <th>{{ __('Default Karat') }}</th>
                <th>{{ __('Shares') }}</th>
                <th>{{ __('Weight In 21') }}</th>
                <th>{{ __('Weight In 24') }}</th>
                <th>{{ __('Item Previous Balance') }}</th>
                <th>{{ __('Item Current Balance') }}</th>
                <th>{{ __('Date') }}</th>
                <th>{{ __('Document ID') }}</th>
                <th>{{ __('Statement') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $report)
                <tr class="fs-5">
                    <td>{{ $report->kind }}</td>
                    <td>{{ $report->kind_name }}</td>
                    @if ($report->type == 'create')
                        <td>
                            0
                        </td>
                        <td>
                            {{ $report->weight }}
                        </td>
                    @else
                        <td>
                            {{ $report->weight }}
                        </td>
                        <td>
                            0
                        </td>
                    @endif
                    <td>
                        {{ $report->karat }}
                    </td>
                    <td class="text-danger">
                        {{ $report->shares }}
                    </td>
                    <td>
                        {{ $report->weight_in_21 }}
                    </td>
                    <td>
                        {{ $report->weight_in_24 }}
                    </td>
                    <td>
                        {{ $report->department_previous_balance }}
                    </td>
                    <td>
                        {{ $report->department_current_balance }}
                    </td>

                    <td>{{ $report->date }}</td>

                    <td>{{ $report->doc_num }}</td>

                    @if ($report->type == 'create')
                        @if ($report->transfer_type == 'to')
                            <td class="text-success">
                                {{ __('Transfer To Office') }}
                            </td>
                        @else
                            <td class="text-success">
                                {{ __('Transfer From Office') }}
                            </td>
                        @endif
                    @elseif($report->type == 'edit')
                        @if ($report->transfer_type == 'to')
                            <td class="text-danger">
                                {{ __('Delete Of Transfer To Office For Editing') }}
                            </td>
                        @else
                            <td class="text-danger">
                                {{ __('Delete Of Transfer From Office For Editing') }}
                            </td>
                        @endif
                    @else
                        @if ($report->transfer_type == 'to')
                            <td class="text-danger">
                                {{ __('Delete Of Transfer To Office') }}
                            </td>
                        @else
                            <td class="text-danger">
                                {{ __('Delete Of Transfer From Office') }}
                            </td>
                        @endif
                    @endif
                </tr>
            @endforeach

        </tbody>
    </table>
@empty
    <p class="text-center text-info fs-2">{{ __('Does Not Exist') }}</p>
@endforelse
