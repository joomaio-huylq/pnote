<button class="btn btn-primary" id="create-user-button" data-bs-toggle="modal" data-bs-target="#createSuperUser" style="margin-right: 3px;">Create User</button>
<!-- Modal -->
<div class="modal fade" id="createSuperUser" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" style="text-align: left;">
    <div class="modal-dialog modal-dialog-centered " style="max-width: 600px;">
        <div class="modal-content container px-3">
            <div class="modal-header">
                <h4 class="modal-title fw-bold" id="popupNoteTypeLabel">Create Super User</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <p id="error-text" style="color: red;"></p>
                    <div class="row px-0">
                        <div class="mb-3 col-12 mx-auto">
                            <label class="form-label fw-bold">Name:</label>
                            <input type="text" class="form-control" name="name" id="name">
                        </div>
                        <div class="mb-3 col-12 mx-auto">
                            <label class="form-label fw-bold">Username:</label>
                            <input type="text" class="form-control" name="username" id="username">
                        </div>
                        <div class="mb-3 col-12 mx-auto">
                            <label class="form-label fw-bold">Email:</label>
                            <input type="email" class="form-control" name="email" id="email">
                        </div>
                        <div class="mb-3 col-12 mx-auto">
                            <label class="form-label fw-bold">Password:</label>
                            <input type="password" class="form-control" name="password" id="password">
                        </div>
                        <div class="mb-3 col-12 mx-auto">
                            <label class="form-label fw-bold">Confirm Password:</label>
                            <input type="password" class="form-control" name="confirm_password" id="confirm_password">
                        </div>
                    </div>
                    <div class="row g-3 align-items-center m-0">
                        <div class="modal-footer">
                            <div class="row">
                                <div class="col-6 text-end pe-0">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                                <div class="col-6 text-end pe-0">
                                    <button type="button" id="submit-form" class="btn btn-outline-success">Create</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $(document).on('click', '#submit-form', function(){
            $.ajax({
                url: '/pnote/create-super-user',
                type: 'POST',
                data: {
                    name: $('#name').val(),
                    username: $('#username').val(),
                    email: $('#email').val(),
                    password: $('#password').val(),
                    confirm_password: $('#confirm_password').val(),
                },
                complete: function(xhr, status) {
                    $('#error-text').html('');
                    console.log(xhr.responseText);
                    let response = JSON.parse(xhr.responseText);
                    if (response.status == 'success') {
                        $('#createSuperUser').modal('hide');
                        showToast('success', response.message);
                        $('#create-user-button').css('display', 'none');
                    } else {
                        $('#error-text').html(response.message);
                    }
                }
            });
        });

        function showToast(status, message) {
            let removeClass = status == 'success' ? 'alert-danger' : 'alert-success';
            let addClass = status == 'success' ? 'alert-success' : 'alert-danger';
            $('.toast-message').removeClass(removeClass).addClass(addClass);
            $('.toast-body').html(message);
            $('.toast-notification').toast('show');
        }
    });
</script>