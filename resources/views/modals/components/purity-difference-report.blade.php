<table class="table text-center">
    <thead class="table-primary">
        <tr>
            <th>{{ __('Kind') }}</th>
            <th>{{ __('Kind Name') }}</th>
            <th>{{ __('Default Karat') }}</th>
            <th>{{ __('Shares') }}</th>
            <th>{{ __('Shares Difference') }}</th>
            <th>{{ __('Weight') }}</th>
            <th>{{ __('Weight')."*".__('Shares Difference') }}</th>
            <th>{{ __('Worker') }}</th>
            <th>{{ __('Date') }}</th>
            <th>{{ __('Document ID') }}</th>
            <th>{{ __('Doc Type') }}</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalDifference = 0;
        @endphp
        @foreach ($purityDifferences as $purityDifference)
            <tr>
                @php
                    $totalDifference += $purityDifference->weight * ($purityDifference->shares - $purityDifference->shares_to_transfer);
                @endphp
                <td>{{ $purityDifference->code }}</td>
                <td>{{ $purityDifference->name }}</td>
                <td>{{ $purityDifference->karat }}</td>
                <td>{{ $purityDifference->actual_shares }}</td>
                <td class="text-danger">{{ $purityDifference->purity_difference}}</td>
                <td>{{ $purityDifference->weight }}</td>
                <td>{{ round(($purityDifference->purity_difference* $purityDifference->weight )/875, 3)}}</td>
                <td>{{ $purityDifference->worker?->name }}</td>
                <td>{{ $purityDifference->date }}</td>
                <td>{{ $purityDifference->doc_id }}</td>
                <td>{{ __(Config::get("definitions.doctypes.{$purityDifference->doc_type}", '')) }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="5"></td>
            <td class="table-info">{{ $purityDifferences->sum('shares') - $purityDifferences->sum('shares_to_transfer') }}</td>
            <td class="table-info">{{ $purityDifferences->sum('weight') }}</td>
            <td class="table-info">{{ $totalDifference }}</td>
            <td colspan="4"></td>
        </tr>
    </tbody>
</table>
