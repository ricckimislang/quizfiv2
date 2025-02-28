<div id="edit-voucher-form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="edit-voucher-form-label"
    aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit-voucher-form-label">Editing Voucher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form id="voucher-edit-form">
                    <input type="hidden" name="voucherid" id="voucherid">
                    <div class="form-group">
                        <label for="modalquantity">Quantity</label>
                        <input type="number" class="form-control" id="modalquantity" name="modalquantity" required>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="voucher-edit-form">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>