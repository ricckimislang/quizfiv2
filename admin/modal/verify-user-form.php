<div id="verification-form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="verification-form-label"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="verification-form-label">User Verification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <!-- for navigation menu -->
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="verification-table-tab" data-bs-toggle="tab"
                        data-bs-target="#verification-table" type="button" role="tab" aria-controls="verification-table"
                        aria-selected="true"><i class="bx bx-table"></i> Verification</button>

                    <button class="nav-link" id="history-logs-tab" data-bs-toggle="tab" data-bs-target="#history-logs"
                        type="button" role="tab" aria-controls="history-logs" aria-selected="false"><i
                            class="bx bx-history"></i> History Logs</button>
                </div>
            </nav>

            <div class="modal-body">
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="verification-table" role="tabpanel">
                        <table id="verifyTable" class="verifyTable table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Student ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Registration Date</th>
                                    <th scope="col">Verification Status</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td data-label='Student ID'>789012</td>
                                    <td data-label='Name'>Jane Doe</td>
                                    <td data-label='Email'>janedoe@example.com</td>
                                    <td data-label='Registration Date'>2022-02-01</td>
                                    <td data-label='Status'>
                                        <div class="text-container bg-info">
                                            Verified
                                        </div>
                                    </td>
                                    <td data-label='Actions'>
                                        <div class="action-btn-group">
                                            <button class="btn btn-sm btn-primary">Verify</button>
                                            <button class="btn btn-sm btn-danger">Reject</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td data-label='Student ID'>345678</td>
                                    <td data-label='Name'>Bob Smith</td>
                                    <td data-label='Email'>bobsmith@example.com</td>
                                    <td data-label='Registration Date'>2022-03-01</td>
                                    <td data-label='Status'>
                                        <div class="text-container bg-info">
                                            Verified
                                        </div>
                                    </td>
                                    <td data-label='Actions'>
                                        <div class="action-btn-group">
                                            <button class="btn btn-sm btn-primary">Verify</button>
                                            <button class="btn btn-sm btn-danger">Reject</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td data-label='Student ID'>901234</td>
                                    <td data-label='Name'>Alice Johnson</td>
                                    <td data-label='Email'>alicejohnson@example.com</td>
                                    <td data-label='Registration Date'>2022-04-01</td>
                                    <td data-label='Status'>
                                        <div class="text-container bg-info">
                                            Verified
                                        </div>
                                    </td>
                                    <td data-label='Actions'>
                                        <div class="action-btn-group">
                                            <button class="btn btn-sm btn-primary">Verify</button>
                                            <button class="btn btn-sm btn-danger">Reject</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td data-label='Student ID'>567890</td>
                                    <td data-label='Name'>Mike Brown</td>
                                    <td data-label='Email'>mikebrown@example.com</td>
                                    <td data-label='Registration Date'>2022-05-01</td>
                                    <td data-label='Status'>
                                        <div class="text-container bg-info">
                                            Verified
                                        </div>
                                    </td>
                                    <td data-label='Actions'>
                                        <div class="action-btn-group">
                                            <button class="btn btn-sm btn-primary">Verify</button>
                                            <button class="btn btn-sm btn-danger">Reject</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="tab-pane fade" id="history-logs" role="tabpanel">
                        <table id="historyTable" class="historyTable table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Student ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Registration Date</th>
                                    <th scope="col">Verification Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td data-label='Student ID'>189012</td>
                                    <td data-label='Name'>Jane Doe</td>
                                    <td data-label='Email'>janedoe@example.com</td>
                                    <td data-label='Registration Date'>2022-02-01</td>
                                    <td data-label='Status'>
                                        <div class="text-container bg-success">
                                            Verified
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td data-label='Student ID'>567890</td>
                                    <td data-label='Name'>Mike Brown</td>
                                    <td data-label='Email'>mikebrown@example.com</td>
                                    <td data-label='Registration Date'>2022-05-01</td>
                                    <td data-label='Status'>
                                        <div class="text-container bg-danger">
                                            Rejected
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>