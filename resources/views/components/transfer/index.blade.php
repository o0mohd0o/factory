 <div class="row">

     <div class="col-8">
         <div id="departments-transfer-section">

             <h1 class="text-center bg-white rounded py-1">{{ $department->name }}</h1>

             @if (session('message'))
                 <div style="    padding: 0.75rem 1.25rem !important;"
                     class="alert alert-{{ session('alert-type') }} alert-dismissible " role="alert" id="session-alert">
                     {{ session('message') }}
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                     </button>
                 </div>
             @endif
             <div class="row p-1" style="direction: rtl;">
                 <div class="col-2">
                     <button class="transfers-navigator" data-transfers-date="{{ $date }}"
                         data-ordering="first"> <i class="fas fa-step-forward"></i> </button>
                     <button class="transfers-navigator" data-transfers-date="{{ $date }}"
                         data-ordering="previous">
                         <i class="fas fa-arrow-right"></i> </button>
                 </div>


                 <div class="col-8"></div>

                 <div class="col-2" style="direction: ltr;">
                     <button class="transfers-navigator" data-transfers-date="{{ $date }}" data-ordering="last">
                         <i class="fas fa-step-backward"></i> </button>
                     <button class="transfers-navigator" data-transfers-date="{{ $date }}" data-ordering="next">
                         <i class="fas fa-arrow-left"></i> </button>
                 </div>

             </div>

             <div class="form-background">
                 <form id="transfer-create-form" action="{{ route('ajax.transfers.store', $department) }}"
                     autocomplete="off" method="post">
                     @csrf

                     <div class="row">
                         <div class="col-sm-2">
                             <div class="form-group">
                                 <label for="value">{{ __('Date') }}</label>
                                 <input type="text" name="date" class="form-control date-picker"
                                     data-date-format="yyyy-mm-dd" value="{{ $date }}">
                             </div>
                         </div>
                     </div>
                     <input autocomplete="false" name="hidden" type="text" style="display:none;">
                     <table id="autocomplete_table" class="printForm create-form autocomplete_table transfers-table">
                         <thead>
                             <tr>
                                 <th>{{ __('Document ID') }}</th>
                                 <th>{{ __('Kind') }}</th>
                                 <th>{{ __('Kind Name') }}</th>
                                 <th>{{ __('Default Karat') }}</th>
                                 <th>{{ __('Shares') }}</th>
                                 <th>{{ __('Shares To Transfer') }}</th>
                                 <th>{{ __('Shares Difference') }}</th>
                                 <th>{{ __('Item Current Balance') }}</th>
                                 <th>{{ __('Weight') }}</th>
                                 <th>{{ __('Total Loss') }}</th>
                                 <th>{{ __('Total Gain') }}</th>
                                 <th>{{ __('Net Weight') }}</th>
                                 <th>{{ __('To Department') }}</th>
                                 <th>{{ __('Person On Charge') }}</th>
                                 <th>{{ __('Confirm Transfer') }}</th>
                                 <th>{{ __('Print') }}</th>
                             </tr>
                         </thead>
                         <tbody class="fs-5">
                             @foreach ($outcomingTransfers as $transfer)
                                 <tr>
                                     <td>{{ $transfer->id }}</td>
                                     <td>{{ $transfer->kind }}</td>
                                     <td>{{ $transfer->kind_name }}</td>
                                     <td>{{ $transfer->karat }}</td>
                                     <td>{{ $transfer->shares }}</td>
                                     <td>{{ $transfer->shares_to_transfer }}</td>
                                     <td class="text-danger">
                                         {{ $transfer->shares - $transfer->shares_to_transfer ? $transfer->shares - $transfer->shares_to_transfer : '-' }}
                                     </td>
                                     <td>{{ $transfer->item_weight_before_transfer }}</td>
                                     <td>{{ $transfer->weight_to_transfer }}</td>
                                     <td>{{ $transfer->total_loss }}</td>
                                     <td>{{ $transfer->total_gain }}</td>
                                     <td>{{ $transfer->net_weight }}</td>
                                     <td>{{ $transfer->transfer_to_name }}</td>
                                     <td>{{ $transfer->person_on_charge }}</td>
                                     <td></td>
                                     <td>
                                         <a href="#" class="print_transfer" id="print_{{ $transfer->id }}">
                                             <i class="fas fa-print" style="color: #0d6efd;"></i>
                                         </a>
                                     </td>
                                 </tr>
                             @endforeach

                             <tr>
                                 <input type="hidden" name="item_id" id="transfered-item-id">
                                 <input type="hidden" name="transfered_from" id="transfered-item-department-id">
                                 <input type="hidden" id="transfer_to" data-department={{ $department->id }}
                                     data-field-name="id" class="form-control" name="transfer_to">
                                 <td>-</td>
                                 <td><input type="text" data-department="{{ $department->id }}" id="kind-code"
                                         data-field-name="kind" class="form-control autocomplete_txt" autofill="off"
                                         autocomplete="off" name="kind"></td>
                                 <td><input type="text" id="kind-name" data-department="{{ $department->id }}"
                                         class="form-control autocomplete_txt" autofill="off"
                                         data-field-name="kind_name" autocomplete="off" name="kind_name"></td>
                                 <td><input type="text" id="kind-karat" data-department="{{ $department->id }}"
                                         class="form-control autocomplete_txt" autofill="off" data-field-name="karat"
                                         autocomplete="off" name="karat"></td>
                                 <td><input type="text" id="shares" data-department="{{ $department->id }}"
                                         class="form-control autocomplete_txt" autofill="off"
                                         data-field-name="shares" autocomplete="off" name="shares"></td>
                                 <td><input type="text" id="shares-to-transfer" class="form-control "
                                         autofill="off" data-field-name="shares-to-transfer"
                                         name="shares_to_transfer"></td>
                                 <td><input type="text" id="shares-difference" class="form-control text-danger"
                                         autofill="off" data-field-name="shares-difference" readonly></td>
                                 <td><input type="text" id="itemWeightBeforeTransfer" class="form-control"
                                         name="item_weight_before_transfer" readonly></td>
                                 <td><input type="text" class="form-control weight-to-transfer"
                                         name="weight_to_transfer" value="0">
                                 </td>
                                 <td><input type="text" class="form-control total-loss" name="total_loss"
                                         value="0">
                                 </td>
                                 <td><input type="text" class="form-control total-gain" name="total_gain"
                                         value="0">
                                 </td>
                                 <td><input type="text" class="form-control net-weight" name="net_weight"
                                         value="0" readonly></td>
                                 <td><input type="text" id="transfer_to_name"
                                         data-department={{ $department->id }} data-field-name="name"
                                         class="form-control autocomplete_department" autofill="off"
                                         autocomplete="off" name="transfer_to_name"></td>

                                 <td><input type="text" class="form-control" name="person_on_charge"
                                         value="{{ session('person_on_charge', '') }}"></td>
                                 <td><button type="submit"
                                         class="btn btn-primary confirm-transfer">{{ __('Confirm Transfer') }}</button>
                                 </td>
                                 <td></td>
                             </tr>
                             <tr>
                                 <td colspan="8"></td>
                                 <td class="bg-success">{{ $outcomingTransfersSum }}</td>
                                 <td class="bg-success">{{ $outcomingTransfers->sum('total_loss') }} </td>
                                 <td class="bg-success">{{ $outcomingTransfers->sum('total_gain') }}</td>
                                 <td class="bg-success">{{ $outcomingTransfers->sum('net_weight') }}</td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                             </tr>
                         </tbody>
                     </table>
                 </form>
             </div>

             <h1 class="text-center bg-white rounded py-1 mt-4">{{ __('Transfers from') }}</h1>

             <div class="form-background col-12">
                 <table style="direction: rtl;" class="w-100 printForm create-form autocomplete_table">
                     <thead>
                         <tr>
                             <th>{{ __('Document ID') }}</th>
                             <th>{{ __('Kind') }}</th>
                             <th>{{ __('Kind Name') }}</th>
                             <th>{{ __('Default Karat') }}</th>
                             <th>{{ __('Shares') }}</th>
                             <th>{{ __('Weight') }}</th>
                             <th>{{ __('Total Loss') }}</th>
                             <th>{{ __('Total Gain') }}</th>
                             <th>{{ __('Net Weight') }}</th>
                             <th>{{ __('From Department ID') }}</th>
                             <th>{{ __('From Department') }}</th>
                             <th>{{ __('Person On Charge') }}</th>
                         </tr>
                     </thead>
                     <tbody class="fs-4">
                         @foreach ($incomingTransfers as $transfer)
                             <tr>
                                 <td>{{ $transfer->id }}</td>
                                 <td>{{ $transfer->kind }}</td>
                                 <td>{{ $transfer->kind_name }}</td>
                                 <td>{{ $transfer->karat }}</td>
                                 <td class="text-danger">{{ $transfer->shares_to_transfer }}</td>
                                 <td>{{ $transfer->weight_to_transfer }}</td>
                                 <td>{{ $transfer->total_loss }}</td>
                                 <td>{{ $transfer->total_gain }}</td>
                                 <td>{{ $transfer->net_weight }}</td>
                                 <td>{{ $transfer->transfer_from }}</td>
                                 <td>{{ $transfer->transfer_from_name }}</td>
                                 <td>{{ $transfer->person_on_charge }}</td>
                             </tr>
                         @endforeach
                         <tr>
                             <td colspan="5"></td>
                             <td class="bg-success">{{ $incomingTransfersSum }}</td>
                             <td class="bg-success">{{ $incomingTransfers->sum('total_loss') }} </td>
                             <td class="bg-success">{{ $incomingTransfers->sum('total_gain') }}</td>
                             <td class="bg-success">{{ $incomingTransfers->sum('net_weight') }}</td>
                             <td></td>
                             <td></td>
                             <td></td>
                         </tr>
                     </tbody>
                 </table>
             </div>
         </div>

     </div>
     <div class="col-4">
         <div id="departments-section">
             @include('components.department.index')
         </div>
     </div>
 </div>

 <script src="{{ asset('js/transfer.js') }}"></script>
 <script>
     $(document).ready(function() {
         $('.date-picker').datepicker({});

         $(".transfers-table")
             .off()
             .on("keydown", "input", function(e) {
                 if ($(this).attr('type') != 'submit') {
                     if (e.which == 40 || e.which == 13) {
                         if (e.which == 13) {
                             $(this).closest('td').next().find('input, button, select').focus();
                             $(this).closest('td').next().find('input').select();
                             e.preventDefault();
                         }
                     }
                 }

             });

         $(".transfers-table")
             .on("click", "input", function(e) {
                 $(this).select();
             });

         $('#transfer-create-form').on('submit', function(e) {
             e.preventDefault();
             let data = new FormData(this);
             let url = $(this).attr('action');


             axios.post(url, data).then((response) => {
                 let data = response.data;
                 if (data.status == 'success') {
                     toastr.success(data.message);
                     let transferIndexUrl =
                         "{{ route('ajax.transfers.index', $department) }}";
                     axios.get(transferIndexUrl).then((response) => {
                         $('#main-content').html(response.data);
                     })
                 } else {
                     toastr.error(data.message);
                 }
             }).catch((error) => {
                 let errors = error.response.data;
                 if (error.response.status == 422) {
                     $.each(errors.errors, function(key, value) {
                         toastr.error(value);
                     });
                 } else {
                     toastr.error(error.response.data.message);
                 }
             });
         });

         $('.transfer-index').on('click', function(e) {
             e.preventDefault();
             axios.get('{{ route('ajax.transfers.index', $department) }}').then((
                 response) => {
                 $('#main-content').html(response.data);
             }).catch((error) => {
                 toastr.error(error.response.data.message);
             })
         });

         $('.date-picker').on('change', function(e) {
             let date = $(this).val();
             axios.get('{{ route('ajax.transfers.index', $department) }}' + '?date=' +
                 date).then((
                 response) => {
                 $('#main-content').html(response.data);
             }).catch((error) => {
                 toastr.error(error.response.data.message);
             })
         });

         $('#shares-to-transfer').on('change', function(e) {
             let shares = parseInt($('#shares').val());
             let sharesToTransfer = parseInt($(this).val());
             $('#shares-difference').val(shares - sharesToTransfer);
         })

         $('.total-loss, .total-gain, .weight-to-transfer').on('change', function(e) {
             let totalLoss = parseInt($('.total-loss').val());
             let totalGain = parseInt($('.total-gain').val());
             let weightToTransfer = parseInt($('.weight-to-transfer').val());
             $('.net-weight').val(totalGain - totalLoss + weightToTransfer);
         });


         $('.transfers-navigator').on('click', function(e) {
             e.preventDefault();
             let date = $(this).data('transfers-date');
             let ordering = $(this).data('ordering');
             axios.get("{{ route('ajax.transfers.navigator', $department) }}", {
                 params: {
                     date: date,
                     ordering: ordering,
                 }
             }).then((response) => {
                 $('#main-content').html(response.data);
             }).catch((error) => {
                 toastr.error(error.response.data.message);
             })

         });
     });
 </script>

 <script>
     if (typeof basePathTransfer === 'undefined') {
         var basePathTransfer = $("#base_path").val();
     }
     $(".print_transfer").on("click", function(e) {
         // e.preventDefault();
         let id = $(this).attr('id');
         let idArr = id.split("_");
         let transferId = idArr[idArr.length - 1];
         // console.log(transferId);
         // console.log(id);
         $.ajax({
             type: "POST",
             url: basePathTransfer + "/print-transfer",
             data: {
                 transferId: JSON.stringify(
                     transferId
                 ),
             },
             success: function(response) {
                 // Log response
                 window.open(basePathTransfer + "/print-transfer", "_blank");
             },
         });
     });
 </script>
