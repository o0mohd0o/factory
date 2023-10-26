<!-- Modal -->
<div class="modal fade" id="purity-difference-report-show" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">

        <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="modal-title" id="exampleModalLabel"> فروق العيار - {{ $department?->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body section-to-print">
                <h2 class="text-center">فروق العيار - {{ $department?->name }}</h2>
                <h3 class="text-center">
                    {{ __('From Date') }}
                    <span class="text-primary">{{ $from }}</span>
                    {{ __('To Date') }}
                    <span class="text-primary">{{ $to }}</span>
                </h3>

                @include('modals.components.purity-difference-report', [
                    'purityDifferences' => $purityDifferences,
                    'department' => $department,
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
        $("#purity-difference-report-show").modal('show');
        $('#print').on('click', function() {
            window.print();
        })
    });
</script>
