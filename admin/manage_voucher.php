<?php
session_start();
include 'includes/header.php';
include 'includes/session.php';
?>

<!-- links -->
<link rel="stylesheet" href="css/manage_voucher.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<body>
    <header class="header" id="header">
        <?php include_once 'includes/nav-top.php' ?>
    </header>

    <?php include_once 'includes/bubble.php'; ?>

    <main class="main" id="main">
        <?php include_once 'includes/mobile-nav.php'; ?>
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <form id="addVoucher">
                        <div class="form-group">
                            <label for="voucher_name">Voucher Name:</label>
                            <input type="text" placeholder="Voucher Name" name="voucher_name" id="voucher_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="voucher_duration">Duration (e.g., 1hr)</label>
                            <select class="form-control" id="voucher_duration" name="voucher_duration" required>
                                <option value="" disabled selected>Select Duration</option>
                                <option value="01:00:00">1 Hour</option>
                                <option value="02:00:00">2 Hours</option>
                                <option value="03:00:00">3 Hours</option>
                                <option value="04:00:00">4 Hours</option>
                                <option value="05:00:00">5 Hours</option>
                                <option value="06:00:00">6 Hours</option>
                                <option value="07:00:00">7 Hours</option>
                                <option value="08:00:00">8 Hours</option>
                                <option value="09:00:00">9 Hours</option>
                                <option value="10:00:00">10 Hours</option>
                                <option value="11:00:00">11 Hours</option>
                                <option value="12:00:00">12 Hours</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="voucher_description">Voucher Description</label>
                            <input type="text" placeholder="Enter Description" class="form-control"
                                id="voucher_description" name="voucher_description" required>
                        </div>
                        <div class="form-group">
                            <label for="voucher_quantity">Quantity</label>
                            <input type="number" placeholder="Enter Quantity" class="form-control" id="voucher_quantity"
                                name="voucher_quantity" required oninput="this.value=this.value.toUpperCase()">
                        </div>
                        <div class="form-group">
                            <label for="voucher_price">Price</label>
                            <input type="number" placeholder="Enter Price" class="form-control" id="voucher_price"
                                name="voucher_price" required oninput="this.value=this.value.toUpperCase()">
                        </div>
                    </form>
                </div>
                <button type="submit" class="btn btn-primary" form="addVoucher">Add</button>
            </div>
            <div class="card">
                <div class="card-header">
                    <h2>Voucher Table</h2>
                </div>
                <div class="card-body">
                    <table id="voucherTable" class="voucherTable stripe">
                        <thead>
                            <tr>
                                <th>Voucher Name</th>
                                <th>Quantity</th>
                                <th>Duration</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM vouchers ORDER BY quantity desc";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td data-label="Voucher Name"><?php echo htmlspecialchars($row['voucher_name']); ?></td>
                                        <td data-label="Quantity"><?php echo htmlspecialchars($row['quantity']); ?></td>
                                        <td data-label="Duration"><?php echo htmlspecialchars($row['duration']); ?></td>
                                        <td data-label="Actions">
                                            <a onclick="editQuantity('<?php echo $row['voucher_id'] ?>', '<?php echo $row['quantity'] ?>')"
                                                class="btn btn-edit" data-toggle="tooltip" data-placement="top"
                                                title="Edit Quantity"><i class='bx bx-edit'></i></a>
                                            <a onclick="delVoucher('<?php echo $row['voucher_id'] ?>')" class="btn btn-delete"
                                                data-toggle="tooltip" data-placement="top" title="Delete Voucher"><i
                                                    class='bx bx-trash'></i></a>
                                        </td>
                                    </tr>
                            <?php endwhile;
                            } else {
                                echo "<tr><td colspan='5' class='text-center'>No Vouchers </td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                    <script>
                        $(document).ready(function() {
                            $('#voucherTable').DataTable({
                                responsive: true,
                                autoWidth: false,
                                columnDefs: [{
                                        targets: 2
                                    } // Column index 1 (Quantity) should be sorted numerically
                                ],
                                order: [
                                    [2, 'asc']
                                ],
                                language: {
                                    search: "_INPUT_",
                                    searchPlaceholder: "Search students...",
                                    lengthMenu: "Show _MENU_ entries",
                                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                                    paginate: {
                                        first: "First",
                                        last: "Last",
                                        next: "Next",
                                        previous: "Prev"
                                    }
                                },
                                pageLength: 5,
                                lengthMenu: [5, 10, 15, 20],
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
        <?php include_once 'modal/delete_voucher_modal.php' ?>
    </main>
    <?php include_once 'modal/edit-voucher-form.php' ?>
    <script src="js/main.js"></script>
    <script>
        $(document).ready(function() {
            $('#addVoucher').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: 'process/add_voucher.php',
                    data: formData,
                    dataType: 'json',
                    success: function(data) {
                        if (data.status === 'success') {
                            toastr.success('Voucher added successfully!', '', {
                                timeOut: 1000
                            });
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        } else {
                            toastr.error('An error occurred: ' + data.message, '', {
                                timeOut: 3000
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log("Error:", xhr.responseText);
                        toastr.error("Error: " + xhr.responseText);
                    }
                });
            });
        });

        function editQuantity(id, quantity) {
            $('#voucherid').val(id);
            $('#modalquantity').val(quantity);
            $('#edit-voucher-form').modal('show');

            $('#voucher-edit-form').submit(function(event) {
                event.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: 'process/update_voucher.php',
                    data: formData,
                    dataType: 'json',
                    success: function(data) {
                        if (data.status === 'success') {
                            toastr.success('Voucher quantity updated successfully!', '', {
                                timeOut: 1000
                            });
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        } else {
                            toastr.error('An error occurred: ' + data.message, '', {
                                timeOut: 3000
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error('An error occurred: ' + status, '', {
                            timeOut: 3000
                        });
                    }
                });
            });
        }

        function delVoucher(voucher_id) {
            $('#deleteConfirmationModal').modal('show');
            const deleteBtn = document.getElementById('delete-user-btn');

            $(deleteBtn).click(function() {
                $.ajax({
                    type: 'POST',
                    url: 'process/delete_voucher.php',
                    data: {
                        voucher_id: voucher_id
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.status === 'success') {
                            toastr.success('Voucher deleted successfully!');
                            $('#deleteConfirmationModal').modal('hide');
                            location.reload();

                        } else {
                            toastr.error('An error occurred: ' + data.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log("Error:", xhr.responseText);
                        toastr.error("Error: " + xhr.responseText);
                    }
                });
            });
        }
    </script>
</body>