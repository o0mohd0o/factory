<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h6 class="page-title">{{ __("Dashboard") }}</h6>
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="#">{{ __("Manage Users") }}</a></li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ __("Manage Users") }}</h4>
                <table id="datatable" class=" userdatatable table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>{{ __("English Name") }}</th>
                            <th>{{ __("Arabic Name") }}</th>
                            <th>{{ __("User Code") }}</th>
                            <th>{{ __("Email") }}</th>
                            <th>{{ __("Permissions") }}</th>
                            <th>{{ __("Action") }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <th>{{ $user->name_en }}</th>
                            <th>{{ $user->name_ar }}</th>
                            <th>{{ $user->user_code }}</th>
                            <th>{{ $user->email }}</th>
                            <th style="max-width: 50%;width: 50%">
                                <div class="row">
                                    @foreach ($user->permissions->pluck('name') as $name)
                                    <div class="col-xl-3">
                                        <a class="btn btn-success" style="margin-bottom: 10px; width: 100%;background-color:#218838;border-color: #218838;">
                                            {{ roleName($name) }}
                                        </a>
                                    </div>
                                    @endforeach
                                </div>

                            </th>
                            <th><a href='#' data-type='edit-userRole' data-id="{{ $user->id }}" id='edit-userrole' data-bs-toggle='modal' data-bs-target='#edit-userRole' style="padding: 6px 34px" class='edit btn btn-info btn-sm'>{{ __('Edit') }}</a></th>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('modals.edit-role');

<script>
    $(function() {
        $(".userdatatable").on('click', "#edit-userrole", function() {
            let id = $(this).data('id');
            $("#user_id").val(id);
            $.ajax({
                url: "{!! route('ajax.manageUsers.userRole') !!}", // Adjust the URL to your server route
                method: "GET"
                , data: {
                    id: id
                }
                , success: function(response) {
                    var roles = response.roles;
                    $(".check-roles").each(function(index, item) {
                        let input = $(this).find("input");
                        if (roles.includes($(input).attr("name"))) {
                            $(input).prop("checked", true);
                        } else {
                            $(input).prop("checked", false);
                        }
                    });
                }
                , error: function() {
                    console.log("Error fetching user role.");
                }
            });
        });
    });

</script>
