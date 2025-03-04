<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content text-center">
            <div class="modal-header bg-danger text-white">
                <h4 class="modal-title" id="deleteConfirmationModalLabel"><i class="bx bx-error-circle"></i> Warning!</h4>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <i class="bx bx-error-circle text-danger" style="font-size: 4rem;"></i>
                <h5 class="mt-3"><strong>This action cannot be undone!</strong></h5>
                <p>Are you sure you want to delete this user? This operation is irreversible.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                    <i class="bx bx-x-circle"></i> Cancel
                </button>
                <button type="button" class="btn btn-danger px-4" id="delete-user-btn">
                    <i class="bx bx-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>
</div>