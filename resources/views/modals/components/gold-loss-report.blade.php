<h2 class=" text-center p-2 m-2  text-danger border border-danger rounded">
    {{ __('Gold Loss') }}
</h2>

<table class="table text-center">
    <thead class="table-primary">
        <tr>
            @if (!$department)
                <th>{{ __('Department') }}</th>
            @endif
            @if (!$worker)
                <th>{{ __('Worker') }}</th>
            @endif
            <th>{{ __('Date') }}</th>
            <th>{{ __('Used Weight') }}</th>
            <th>{{ __('New Weight') }}</th>
            <th>{{ __('Total Loss') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($goldLosses as $goldLoss)
            <tr class="fs-5">
                @if (!$department)
                    <td>{{ $goldLoss->department->name }}</td>
                @endif
                @if (!$worker)
                    <td>{{ $goldLoss->worker?->name_ar }}</td>
                @endif
                <td>{{ $goldLoss->date }}</td>
                <td> {{ roundAndFormat($goldLoss->total_used_gold_in_21) }}</td>
                <td> {{ roundAndFormat($goldLoss->total_used_gold_in_21 + $goldLoss->loss_weight_in_21) }}</td>
                <td> {{ roundAndFormat($goldLoss->loss_weight_in_21) }}</td>
            </tr>
        @endforeach
        <tr>
            @if (!$department && !$worker)
                <td colspan="3"></td>
            @elseif(!$department && $worker)
                <td colspan="2"></td>
            @elseif($department && !$worker)
                <td colspan="2"></td>
            @elseif($department && $worker)
                <td></td>
            @endif
            <td> {{ roundAndFormat($goldLosses->sum('total_used_gold_in_21')) }}</td>
            <td> {{ roundAndFormat($goldLosses->sum('total_used_gold_in_21') - $goldLosses->sum('loss_weight_in_21')) }}
            </td>
            <td> {{ roundAndFormat($goldLosses->sum('loss_weight_in_21')) }}</td>
        </tr>

    </tbody>
</table>
