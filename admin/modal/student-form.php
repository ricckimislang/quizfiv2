<div id="student-form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="student-form-label"
    aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="student-form-label">Student Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-profile-tab" data-bs-toggle="tab"
                        data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile"
                        aria-selected="true"><i class="bx bxs-user-detail"></i> Profile</button>

                    <button class="nav-link" id="nav-account-tab" data-bs-toggle="tab" data-bs-target="#nav-account"
                        type="button" role="tab" aria-controls="nav-account" aria-selected="false"><i
                            class="bx bx-user"></i> Account</button>
                </div>
            </nav>
            <div class="modal-body">
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-profile" role="tabpanel"
                        aria-labelledby="nav-home-tab">
                        <form id="student-edit-form">
                            <div class="form-group">
                                <label for="modalfirst_name">First Name:</label>
                                <input type="text" class="form-control" id="modalfirst_name" name="modalfirst_name"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="modallast_name">Last Name:</label>
                                <input type="text" class="form-control" id="modallast_name" name="modallast_name"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="modalyear_level">Year</label>
                                <select name="modalyear_level" id="modalyear_level" class="form-control" required>
                                    <option value="" disabled selected>Select Year</option>
                                    <option value="1st">1st Year</option>
                                    <option value="2nd">2nd Year</option>
                                    <option value="3rd">3rd Year</option>
                                    <option value="4th">4th Year</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="modalstudent_department">Department:</label>
                                <input type="text" class="form-control" id="modalstudent_department"
                                    name="modalstudent_department" required>
                            </div>
                        </form>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" form="student-edit-form">Submit</button>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="nav-account" role="tabpanel" aria-labelledby="nav-home-tab">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>