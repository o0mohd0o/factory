<!-- Modal -->
<div class="modal fade" id="gold-loss-report-show" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">

        <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="modal-title" id="exampleModalLabel"> {{ __('Gold Loss') . '-' . $department?->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body section-to-print">
                @if ($department)
                <h2 class="text-center">{{ __('Gold Loss') . '-' . $department->name }}</h2>
                @endif
                @if ($worker)
                    <h3 class="text-center">{{ __('Worker') . '-' . $worker->name }}</h3>
                @endif
                    <h3 class="text-center">
                        {{ __('From Date') }}
                        <span class="text-primary">{{ request()->get('from') }}</span>
                        {{ __('To Date') }}
                        <span class="text-primary">{{ request()->get('to') }}</span>
                    </h3>



                    @include('modals.components.gold-loss-report', [
                        'goldLosses' => $goldLosses,
                        'department' => $department,
                        'worker' => $worker,
                    ])


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
        $("#gold-loss-report-show").modal('show');
        $('#print').on('click', function() {
            window.print();
        })
    });
</script>
