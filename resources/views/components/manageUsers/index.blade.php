<table id="users-table" class="table" style="direction: ltr">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name En</th>
            <th>Name Ar</th>
            <th>Code</th>
            <th>Email</th>
            <th>Created At</th>
            <th>action</th>
        </tr>
    </thead>
</table>

@include('modals.edit-role');

<script>
    $(function() {
        $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('ajax.manageUsers.users') !!}',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name_en', name: 'name_en' },
                { data: 'name_ar', name: 'name_ar' },
                { data: 'user_code', name: 'user_code' },
                { data: 'email', name: 'email' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false }

            ]
        });

        $("#users-table").on('click', "#edit-userrole", function () {
            let id = $(this).data('id');
            $("#user_id").val(id);

            $.ajax({
                url: "{!! route('ajax.manageUsers.userRole') !!}", // Adjust the URL to your server route
                method: "GET",
                data: { id: id },
                success: function(response) {
                    var roles = response.roles;
                    $(".check-roles").each(function(index, item) {
                        let input = $(this).find("input");
                        if(roles.includes($(input).attr("name"))) {
                            $(input).prop("checked", true);
                        } else {
                            $(input).prop("checked", false);
                        }
                    });
                },
                error: function() {
                    console.log("Error fetching user role.");
                }
            });
        });
    });
</script>
