 <!-- Breadcrumbs-->
 <h1 class="text-center bg-white rounded py-1">{{ $department->name }}</h1>

 <ol class="breadcrumb" style="padding:10px; justify-content: flex-end;">
     <li class="breadcrumb-item" style="direction: rtl;">{{ $department->name }}</li>
     <li class="breadcrumb-item" style="direction: rtl;">{{ __('Transfer Document') }}</li>
     <li class="breadcrumb-item">
         <a href="{{ route('home') }}">{{ __('Home') }}</a>
     </li>

 </ol>
 <div class="row" style="text-align: right;">
     <a href="{{ route('transfer.index') }}"><button class="btn-primary btn-sm transfer-index"
             style="direction: rtl">{{ __('Transfer Documents List') }}</button></a>
 </div>

 @if (session('message'))
     <div style="    padding: 0.75rem 1.25rem !important;"
         class="alert alert-{{ session('alert-type') }} alert-dismissible " role="alert" id="session-alert">
         {{ session('message') }}
         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
             <span aria-hidden="true">&times;</span>
         </button>
     </div>
 @endif


 <div class="form-background">
     <form id="transfer-create-form" action="{{ route('ajax.transfers.store', $department) }}" autocomplete="off"
         method="post">
         @csrf
         <div class="row">
             <div class="col-sm-2">
                 <div class="form-group">
                     <label for="value">{{ __('Document ID') }}</label>
                     <input type="text"
                         value="@if (!empty($last->id)) {{ $last->id + 1 }}@else{{ 1 }} @endif"
                         class="form-control" readonly>
                 </div>
             </div>
             <div class="col-sm-2" style="position: absolute;left: 50px;">
                 <div class="form-group">
                     <label for="value">{{ __('Person On Charge') }}</label>
                     <input type="text" name="person_on_charge" class="form-control">
                 </div>
             </div>
         </div>
         <div class="row">
             <div class="col-sm-2">
                 <div class="form-group">
                     <label for="value">{{ __('Date') }}</label>
                     <input type="text" name="date" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                 </div>
             </div>
         </div>
         <input autocomplete="false" name="hidden" type="text" style="display:none;">
         <table id="autocomplete_table" class="printForm create-form autocomplete_table">
             <thead>
                 <tr>
                     <th>{{ __('Material ID') }}</th>
                     <th>{{ __('Material Name') }}</th>
                     <th>{{ __('Item Previous Balance') }}</th>
                     <th>{{ __('Coming') }}</th>
                     <th>{{ __('Going') }}</th>
                     <th>{{ __('Certified Loss') }}</th>
                     <th>{{ __('Not Certified Loss') }}</th>
                     <th>{{ __('Other Loss') }}</th>
                     <th>{{ __('Add Stones') }}</th>
                     <th>{{ __('Add Copper') }}</th>
                     <th>{{ __('Add Other') }}</th>
                     <th>{{ __('Add Other') }}</th>
                     <th>{{ __('Add Other') }}</th>
                     <th>{{ __('Item Current Balance') }}</th>
                     <th>{{ __('Transfer To') }}</th>
                 </tr>
             </thead>
             <tbody>
                 <tr class="addrow" id="row_1">
                     <td><input name="item_id[]" data-field-name="id" id="itemID_1"
                             class="item-id form-control autocomplete_txt" autofill="off" autocomplete="off"></td>
                     <td><input name="item_name[]" data-field-name="item_name" id="itemName_1"
                             class="itemName form-control autocomplete_txt" autofill="off" autocomplete="off"></td>
                     <td><input name="previous_balance[]" data-field-name="previousـbalance" id="previousـbalance_1"
                             class="form-control" autofill="off" autocomplete="off"></td>
                     <td><input name="coming[]" data-field-name="coming" id="coming_1" class="form-control"
                             autofill="off" autocomplete="off"></td>
                     <td><input name="going[]" data-field-name="going" id="going_1" class="form-control"
                             autofill="off" autocomplete="off"></td>
                     <td><input name="certified_loss[]" data-field-name="certified_loss" id="certified_loss_1"
                             class="fare form-control" autofill="off" autocomplete="off"></td>
                     <td><input name="not_certified_loss[]" data-field-name="not_certified_loss"
                             id="not_certified_loss_1" class="form-control" autofill="off" autocomplete="off"></td>
                     <td><input name="other_loss[]" data-field-name="other_loss" id="other_loss_1"
                             class="form-control" autofill="off" autocomplete="off"></td>
                     <td><input name="add_stones[]" data-field-name="add_stones" id="add_stones_1"
                             class="form-control" autofill="off" autocomplete="off"></td>
                     <td><input name="add_copper[]" data-field-name="add_copper" id="add_copper_1"
                             class="form-control" autofill="off" autocomplete="off"></td>
                     <td><input name="add_other1[]" data-field-name="add_other1" id="add_other1_1"
                             class="form-control" autofill="off" autocomplete="off"></td>
                     <td><input name="add_other2[]" data-field-name="add_other2" id="add_other2_1"
                             class="form-control" autofill="off" autocomplete="off"></td>
                     <td><input name="add_other3[]" data-field-name="add_other3" id="add_other3_1"
                             class="form-control" autofill="off" autocomplete="off"></td>
                     <td><input name="current_balance[]" data-field-name="current_balance" id="current_balance_1"
                             class="form-control" autofill="off" autocomplete="off"></td>

                     <td><input name="transfer_to_name[]" data-field-name="name" id="transferTo_1"
                             class="form-control autocomplete_department" autofill="off" autocomplete="off">
                         <input type="hidden" name="transfer_to[]" data-field-name="transfer_from_hidden"
                             id="transfer_to_hidden_1" class="autocomplete_department">
                     </td>
                 </tr>
             </tbody>
         </table>


         <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
         <button type="button" id="print_labels" class="btn btn-primary">
             {{ __('Print') }}
         </button>

     </form>
 </div>
 </div>
 <script src="{{ asset('js/transfer.js') }}"></script>

 <script>
     $(document).ready(function() {
         $('#transfer-create-form').on('submit', function(e) {
             e.preventDefault();
             let data = new FormData(this);
             let url = $(this).attr('action');

             axios.post(url, data).then((response) => {
                 let data = response.data;
                 if (data.status == 'success') {
                     toastr.success(data.message);
                     let createUrl = "{{ route('ajax.transfers.create', $department) }}";
                     axios.get(createUrl).then((response) => {
                         $('#main-content').html(response.data);
                     })
                 } else {
                     toastr.error(data.message);
                 }
             }).catch((error) => {
                 let errors = error.response.data;
                 if (error.response.status == 422) {
                     $.each(errors.errors, function(key, value) {
                         toastr.error( value);
                     });
                 } else {
                     toastr.error(error.response.data.message);
                 }
             });
         });

         $('.transfer-index').on('click', function(e) {
             e.preventDefault();
             console.log('{{ route('ajax.transfers.index', $department) }}');
             axios.get('{{ route('ajax.transfers.index', $department) }}').then((response) => {
                 $('#main-content').html(response.data);
             }).catch((error) => {
                 toastr.error(error);
             })
         });
     });
 </script>
