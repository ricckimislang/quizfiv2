<!-- Change Password Modal -->
<div class="modal fade" id="change-password-modal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="passwordModalLabel" style="color: black;">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="changePasswordForm">
                    <div class="mb-3">
                        <label for="currentPassword" class="form-label" style="color: black;">Current Password</label>
                        <input type="password" class="form-control" style="border: 1px solid rgba(0, 0, 0, 0.1); color: black;" id="currentPassword" name="currentPassword" required>
                    </div>
                    <div class="mb-3">
                        <label for="newPassword" class="form-label" style="color: black;">New Password</label>
                        <input type="password" class="form-control" style="border: 1px solid rgba(0, 0, 0, 0.1); color: black;" id="newPassword" name="newPassword" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label" style="color: black;">Confirm New Password</label>
                        <input type="password" class="form-control" style="border: 1px solid rgba(0, 0, 0, 0.1); color: black;" id="confirmPassword" name="confirmPassword" required>
                    </div>
                    <input type="hidden" name="password_user_id" value="<?php echo $user_id; ?>">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="passbtn btn primary-btn" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="passbtn btn primary-btn" id="changePasswordBtn" form="changePasswordForm">Save Changes</button>
            </div>
        </div>
    </div>
</div>