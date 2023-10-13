@forelse ($departmentStatements->groupBy('item_id') as $itemStatements)
    <h2 class=" text-center p-2 m-2  text-danger border border-danger rounded">
        {{ "{$itemStatements->first()?->item?->name}-{$itemStatements->first()?->item?->code}" }}
    </h2>
    <table class="table text-center">
        <thead class="table-primary">
            <tr>
                <th>{{ __('Kind') }}</th>
                <th>{{ __('Kind Name') }}</th>
                <th>{{ __('Default Karat') }}</th>
                <th>{{ __('Shares') }}</th>
                <th>{{ __('Credit') }}</th>
                <th>{{ __('Debit') }}</th>
                <th>{{ __('Weight In 21') }}</th>
                <th>{{ __('Weight In 24') }}</th>
                <th>{{ __('Item Previous Balance') }}</th>
                <th>{{ __('Item Current Balance') }}</th>
                <th>{{ __('Date') }}</th>
                <th>{{ __('Document ID') }}</th>
                <th>{{ __('Document Type') }}</th>
            </tr>
        </thead>
        <tbody>
            @php
                $currentItemWeight = 0;
                $previousItemWeight = 0;
                $debitIn21 = 0;
                $totalDebitIn21 = 0;
                $creditIn21 = 0;
                $totalCreditIn21 = 0;
            @endphp
            @foreach ($itemStatements as $key => $itemStatement)
                @php
                    if ($itemStatement->debit) {
                        $debitIn21 = ($itemStatement->debit * $itemStatement->actual_shares) / 875;
                        $totalDebitIn21 += $debitIn21;
                        if ($key == 0) {
                            $currentItemWeight += $itemStatement->debit;
                        } else {
                            $previousItemWeight -= $itemStatement->debit;
                            $currentItemWeight += $itemStatement->debit;
                        }
                    } else {
                        $creditIn21 = ($itemStatement->credit * $itemStatement->actual_shares) / 875;
                        $totalCreditIn21 += $creditIn21;
                        if ($key == 0) {
                            $currentItemWeight -= $itemStatement->credit;
                        } else {
                            $currentItemWeight -= $itemStatement->credit;
                            $previousItemWeight += $itemStatement->credit;
                        }
                    }
                @endphp
                <tr class="fs-5">
                    <td>{{ $itemStatement->item->code }}</td>
                    <td>{{ $itemStatement->item->name }}</td>
                    <td>{{ $itemStatement->item->karat }}</td>
                    <td>{{ $itemStatement->actual_shares }}</td>
                    <td>
                        {{ $itemStatement->credit }}
                    </td>
                    <td>
                        {{ $itemStatement->debit }}
                    </td>
                    @if ($itemStatement->debit)
                        <td>
                            {{ $debitIn21 }}
                        </td>
                    @else
                        <td>
                            {{ $creditIn21 }}
                        </td>
                    @endif

                    @if ($itemStatement->debit)
                        <td>
                            {{ ($debitIn21 * 21) / 24 }}
                        </td>
                    @else
                        <td>
                            {{ ($creditIn21 * 21) / 24 }}
                        </td>
                    @endif
                    <td>
                        {{ $previousItemWeight }}
                    </td>
                    <td>
                        {{ $currentItemWeight }}
                    </td>
                    <td>{{ $itemStatement->date }}</td>

                    <td>{{ $itemStatement->doc_id }}</td>
                    <td>{{ __(Config::get("definitions.doctypes.{$itemStatement->doc_type}", '')) }}</td>
            @endforeach
            <tr>
                <td colspan="4"></td>
                <td>
                    {{ $itemStatements->sum('credit') }}
                </td>
                <td>
                    {{ $itemStatements->sum('debit') }}
                </td>
                <td>
                    {{ $totalDebitIn21 - $totalCreditIn21 }}
                </td>
                <td>
                    {{ (($totalDebitIn21 - $totalCreditIn21) * 21) / 24 }}
                </td>
                <td>
                    {{ $previousItemWeight }}
                </td>
                <td>
                    {{ $currentItemWeight }}
                </td>
                <td colspan="3"></td>
            </tr>
        </tbody>
    </table>
@empty
    <p class="text-center text-info fs-2">{{ __('Does Not Exist') }}</p>
@endforelse
