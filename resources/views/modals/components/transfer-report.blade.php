<h2 class=" text-center p-2 m-2  text-danger border border-danger rounded">
    {{ __('Transfers Between Department') }}
</h2>
@forelse ($transferReports as $day => $reports)
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
                <th>{{ __('Shares To Transfer') }}</th>
                <th>{{ __('Shares Difference') }}</th>
                <th>{{ __('Weight In 21') }}</th>
                <th>{{ __('Weight In 24') }}</th>
                <th>{{ __('Item Previous Balance') }}</th>
                <th>{{ __('Item Current Balance') }}</th>
                <th>{{ __('Date') }}</th>
                <th>{{ __('Doc Type') }}</th>
                <th>{{ __('Document ID') }}</th>
                <th>{{ __('Statement') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $report)
                <tr>
                    <td>{{ $report->kind }}</td>
                    <td>{{ $report->kind_name }}</td>
                    @if ($report->transfer_to == $department->id)
                        <td>
                            0
                        </td>
                        <td>
                            {{ $report->weight }}
                        </td>
                        <td>
                            {{ $report->karat }}
                        </td>
                        <td class="text-danger">
                            {{ $report->shares_to_transfer }}
                        </td>
                        <td></td>
                        <td></td>
                        <td>
                            {{ $report->weight_in_21 }}
                        </td>
                        <td>
                            {{ $report->weight_in_24 }}
                        </td>
                        <td>
                            {{ $report->transfer_to_previous_balance }}
                        </td>
                        <td>
                            {{ $report->transfer_to_current_balance }}
                        </td>
                    @else
                        <td>
                            {{ $report->weight }}
                        </td>
                        <td>
                            0
                        </td>
                        <td>
                            {{ $report->karat }}
                        </td>
                        <td class="text-danger">
                            {{ $report->shares }}
                        </td>
                        <td class="text-danger">
                            {{ $report->shares_to_transfer }}
                        </td>
                        <td class="text-danger">
                            {{ $report->shares - $report->shares_to_transfer }}
                        </td>
                        <td>
                            {{ $report->weight_in_21 }}
                        </td>
                        <td>
                            {{ $report->weight_in_24 }}
                        </td>
                        <td>
                            {{ $report->transfer_from_previous_balance }}
                        </td>
                        <td>
                            {{ $report->transfer_from_current_balance }}
                        </td>
                    @endif

                    <td>{{ $report->date }}</td>
                    <td>
                        @if ($report->transfer_to == $department->id)
                            {{ __('Transfer From') }}
                        @else
                            {{ __('Transfer To') }}
                        @endif
                    </td>
                    <td>{{ $report->doc_num }}</td>
                    <td>
                        @if ($report->transfer_to == $department->id)
                            {{ __('Transfer From') }} - {{ $report->transfer_from_name }}
                        @else
                            {{ __('Transfer To') }} - {{ $report->transfer_to_name }}
                        @endif
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>
@empty
    <p  class="text-center text-info fs-2">{{ __('Does Not Exist') }}</p>
@endforelse
