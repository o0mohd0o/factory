
@forelse ($transferReports as $day => $reports)
    <h3 class="transfer-day text-center  m-2  text-info border border-info rounded">
        {{ __('Day') . '  ' . $day }}
    </h3>

    <table class="table text-center">
        <thead class="table-primary">
            <tr>
                <th>{{ __('Kind') }}</th>
                <th>{{ __('Kind Name') }}</th>
                <th>{{ __('Default Karat') }}</th>
                <th>{{ __('Shares') }}</th>
                <th>{{ __('Shares To Transfer') }}</th>
                <th>{{ __('Shares Difference') }}</th>
                <th>{{ __('Transfered Weight') }}</th>
                <th>{{ __('Transfered Weight * Shares Difference') }}</th>
                <th>{{ __('Date') }}</th>
                <th>{{ __('Doc Type') }}</th>
                <th>{{ __('Document ID') }}</th>
                <th>{{ __('Statement') }}</th>
            </tr>
        </thead>
        <tbody>
            @php
               $totalDifference = 0; 
            @endphp
            @foreach ($reports as $report)
                <tr>
                    @php
                        $totalDifference += $report->weight*($report->shares - $report->shares_to_transfer)
                    @endphp
                    <td>{{ $report->kind }}</td>
                    <td>{{ $report->kind_name }}</td>
                    <td>{{ $report->karat }}</td>
                    <td>{{ $report->shares }}</td>
                    <td>{{ $report->shares_to_transfer }}</td>
                    <td class="text-danger">{{ $report->shares - $report->shares_to_transfer }}</td>
                    <td>{{ $report->weight }}</td>
                    <td>{{  ($report->shares - $report->shares_to_transfer) * $report->weight }}</td>
                    <td>{{ $report->date }}</td>
                    <td>

                            {{ __('Transfer To') }}
                    </td>
                    <td>{{ $report->doc_num }}</td>
                    <td>

                            {{ __('Transfer To') }} - {{ $report->transfer_to_name }}
                    </td>
                </tr>
            @endforeach
           <tr> 
            <td colspan="5"></td>
            <td class="table-info">{{$reports->sum('shares') - $reports->sum('shares_to_transfer')}}</td>
            <td class="table-info">{{$reports->sum('weight')}}</td>
            <td class="table-info">{{$totalDifference}}</td>
            <td colspan="4"></td>
           </tr>

        </tbody>
    </table>
@empty
    <p  class="text-center text-info fs-2">{{ __('Does Not Exist') }}</p>
@endforelse
