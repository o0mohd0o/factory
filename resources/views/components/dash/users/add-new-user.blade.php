<style>
    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }

</style>
<!-- start page title -->
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-12">
            <h6 class="page-title">{{ __("Users") }}</h6>
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="#">{{ __("Add New User") }}</a></li>
            </ol>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{  route('ajax.newUser.add')  }}" id="userForm" method="post">
                    @csrf
                    <div class="row mb-3">
                        <label for="example-text-input" class="col-sm-2 col-form-label">{{ __("Arabic Name") }}</label>
                        <div class="col-sm-10">
                            <input class="form-control" name="name_ar" type="text" placeholder="احمد علي" id="example-text-input" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="example-search-input" class="col-sm-2 col-form-label">{{ __("English Name") }}</label>
                        <div class="col-sm-10">
                            <input class="form-control" name="name_en" type="search" placeholder="Ahmed Ali" id="example-search-input" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="example-email-input" class="col-sm-2 col-form-label">{{ __("Email") }}</label>
                        <div class="col-sm-10">
                            <input class="form-control" name="email" type="email" placeholder="tech@gmail.com" id="example-email-input" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="password" class="col-sm-2 col-form-label">{{ __("Password") }}</label>
                        <div class="col-sm-10">
                            <input class="form-control" name="password" type="password" placeholder="****" id="password" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="confirmPassword" class="col-sm-2 col-form-label">{{ __("Confirm Password") }}</label>
                        <div class="col-sm-10">
                            <input class="form-control" name="password_confirmation" type="password" placeholder="****" id="confirmPassword" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="example-password-input" class="col-sm-2 col-form-label">{{ __("User Code") }}</label>
                        <div class="col-sm-10">
                            <input class="form-control" name="user_code" type="number" value="" id="0000" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="d-grid">
                                <input type="submit" class="btn btn-primary btn-lg w-100 waves-effect waves-light" value="{{ __('Save') }}">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

<div id="mo-container">

</div>

<script>
    $(function() {
        $("#userForm").on('submit', function(e) {
            e.preventDefault();
            let url = $(this).attr('action');
            let data = new FormData(this);
            axios.post(url, data).then((response) => {
                let data = response.data;
                toastr.success(data.message);
                $("#mo-container").html(data.html);
                $("#user_id").val(data.user_id);
                var roles = data.roles;
                $(".check-roles").each(function(index, item) {
                    let input = $(this).find("input");
                    if (roles.includes($(input).attr("name"))) {
                        $(input).prop("checked", true);
                    } else {
                        $(input).prop("checked", false);
                    }
                });
                $("#edit-userRole").modal('show');
                $("#userForm")[0].reset();
            }).catch((error) => {
                let errors = error.response.data;
                if (error.response.status == 422) {
                    $.each(errors.errors, function(key, value) {
                        toastr.error(key + ":" + value);
                    });
                } else {
                    toastr.error(error.response.data.message);
                }
            })
        });
    });

</script>
