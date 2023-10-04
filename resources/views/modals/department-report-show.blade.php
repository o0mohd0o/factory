<!-- Modal -->
<div class="modal fade" id="department-report-show" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">

        <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="modal-title" id="exampleModalLabel"> كشف الحساب - {{ $department->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body section-to-print">
                <h2 class="text-center">كشف الحساب - {{ $department->name }}</h2>
                <h3 class="text-center">
                    {{ __('From Date') }}
                    <span class="text-primary">{{ $from }}</span>
                    {{ __('To Date') }}
                    <span class="text-primary">{{ $to }}</span>
                </h3>




                @if ($department->main_department)
                    @include('modals.components.office-transfer-report', [
                        'officeTransfersReports' => $officeTransfersReports,
                        'department' => $department,
                    ])
                @endif

                @include('modals.components.transfer-report', [
                    'transferReports' => $transferReports,
                    'department' => $department,
                ])

                @include('modals.components.opening-balance-report', [
                    'openingBalancesReports' => $openingBalancesReports,
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
        $("#department-report-show").modal('show');
        $('#print').on('click', function() {
            window.print();
        })
    });
</script>
