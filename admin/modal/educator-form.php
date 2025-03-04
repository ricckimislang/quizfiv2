<div id="educator-form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="educator-form-label"
    aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="educator-form-label">Educator Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="educator-profile-tab" data-bs-toggle="tab"
                        data-bs-target="#educator-profile" type="button" role="tab" aria-controls="educator-profile"
                        aria-selected="true"><i class="bx bxs-user-detail"></i> Profile</button>

                    <button class="nav-link" id="educator-account-tab" data-bs-toggle="tab"
                        data-bs-target="#educator-account" type="button" role="tab" aria-controls="educator-account"
                        aria-selected="false"><i class="bx bx-user"></i> Account</button>
                </div>
            </nav>
            <div class="modal-body">
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="educator-profile" role="tabpanel"
                        aria-labelledby="nav-home-tab">
                        <form id="educator-profile-edit-form">
                            <input type="hidden" id="educator_id" name="educator_id">
                            <div class="form-group">
                                <label for="educator_firstname">First Name:</label>
                                <input type="text" class="form-control" id="educator_firstname"
                                    name="educator_firstname" required>
                            </div>
                            <div class="form-group">
                                <label for="educator_lastname">Last Name:</label>
                                <input type="text" class="form-control" id="educator_lastname" name="educator_lastname"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="educator_department">Department:</label>
                                <input type="text" class="form-control" id="educator_department"
                                    name="educator_department" required>
                            </div>
                        </form>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary"
                                form="educator-profile-edit-form">Update Profile</button>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="educator-account" role="tabpanel" aria-labelledby="nav-home-tab">
                        <form id="educator-account-edit-form">
                            <input type="hidden" id="educator_id2" name="educator_id2">
                            <div class="form-group">
                                <label for="educator_account_email">Email:</label>
                                <input type="email" class="form-control" id="educator_account_email"
                                    name="educator_account_email" required>
                            </div>
                            <div class="form-group">
                                <label for="educator_account_user">Username:</label>
                                <input type="text" class="form-control" id="educator_account_user"
                                    name="educator_account_user" required>
                            </div>
                        </form>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary"
                                form="educator-account-edit-form">Update Account</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>