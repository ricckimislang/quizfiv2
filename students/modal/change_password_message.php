<!-- Change Password Warning Modal -->
<div class="modal fade" id="change-password-warning-modal" tabindex="-1" aria-labelledby="passwordWarningModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content" style="border-radius: 10px;">
            <div class="modal-header" style="border-bottom: none; padding: 20px 20px 0px 20px;">
                <h5 class="modal-title" id="passwordWarningModalLabel" style="font-size: 1.2rem; font-weight: 600;">Important Notice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 20px; text-align: center;">
                <div style="margin-bottom: 15px;">
                    <i class="bx bx-error-circle" style="font-size: 3rem; color: #ffc107; margin-bottom: 15px;"></i>
                </div>
                <p style="color: #555; font-size: 0.95rem; margin-bottom: 10px;">
                    For security purposes, please change your password from the default one provided.
                </p>
                <p style="color: #666; font-size: 0.85rem;">
                    This will help ensure the security of your account.
                </p>
            </div>
            <div class="modal-footer" style="border-top: none; padding: 0px 20px 20px 20px;">
                <button type="button" class="btn primary-btn" onclick="changePassword()" style="background-color: #6b48ff; color: white;">
                    Change Password Now
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Later</button>
            </div>
        </div>
    </div>
</div>