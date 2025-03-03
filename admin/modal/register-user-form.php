<div id="registration-form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="registration-form-label"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registration-form-label">User Resgistration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <!-- for navigation menu -->
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="student-reg-tab" data-bs-toggle="tab"
                        data-bs-target="#student-reg" type="button" role="tab" aria-controls="student-reg"
                        aria-selected="true"><i class="bx bxs-user"></i> Student</button>

                    <button class="nav-link" id="educator-reg-tab" data-bs-toggle="tab" data-bs-target="#educator-reg"
                        type="button" role="tab" aria-controls="educator-reg" aria-selected="false"><i
                            class="bx bxs-graduation"></i> Educator</button>
                </div>
            </nav>
            <div class="modal-body">
                <div class="tab-content" id="nav-tabContent">
                    <!-- student -->
                    <div class="tab-pane fade show active" id="student-reg" role="tabpanel"
                        aria-labelledby="nav-home-tab">
                        <form id="student-reg-form">
                            <div class="form-group">
                                <label for="studentEmail-reg">Email:</label>
                                <input type="text" class="form-control" id="studentEmail-reg" name="studentEmail-reg"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="studentFname-reg">First Name:</label>
                                <input type="text" class="form-control" id="studentFname-reg" name="studentFname-reg"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="studentLname-reg">Last Name:</label>
                                <input type="text" class="form-control" id="studentLname-reg" name="studentLname-reg"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="studentYear-reg">Year</label>
                                <select name="studentYear-reg" id="studentYear-reg" class="form-control" required>
                                    <option value="" disabled selected>Select Year</option>
                                    <option value="1st">1st Year</option>
                                    <option value="2nd">2nd Year</option>
                                    <option value="3rd">3rd Year</option>
                                    <option value="4th">4th Year</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="studentDepartment-reg">Department:</label>
                                <input type="text" class="form-control" id="studentDepartment-reg"
                                    name="studentDepartment-reg" required>
                            </div>
                        </form>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" form="student-reg-form">Register</button>
                        </div>
                    </div>
                    <!-- educator -->
                    <div class="tab-pane fade" id="educator-reg" role="tabpanel" aria-labelledby="nav-home-tab">
                        <form id="educator-reg-form">
                            <div class="form-group">
                                <label for="educatorEmail-reg">Email:</label>
                                <input type="text" class="form-control" id="educatorEmail-reg" name="educatorEmail-reg"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="educatorFname-reg">First Name:</label>
                                <input type="text" class="form-control" id="educatorFname-reg" name="educatorFname-reg"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="educatorLname-reg">Last Name:</label>
                                <input type="text" class="form-control" id="educatorLname-reg" name="educatorLname-reg"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="educatorDepartment-reg">Department:</label>
                                <input type="text" class="form-control" id="educatorDepartment-reg"
                                    name="educatorDepartment-reg" required>
                            </div>
                        </form>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" form="educator-reg-form">Register</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>