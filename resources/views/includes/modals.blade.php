@if(auth()->user()->hasRole('accountant'))
@include('modals.department-report-query')
@include('modals.department-daily-report-query')
@include('modals.department-daily-report-in-total-query')
@include('modals.general-settings-query')
@include('modals.department-report-karat-difference-query')
@endif
