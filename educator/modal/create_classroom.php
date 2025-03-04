<div id="create-classroom" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="create-classroom-label"
    aria-hidden="true">
    <div class="modal-dialog modal-m" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="create-classroom-label">Create a classroom</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form id="create-classroom-form">
                    <div class="form-group">
                        <p style="color:  rgb(224, 224, 224)">Note: You can create up to 4 classrooms.</p>
                    </div>
                    <div class="form-group">
                        <label for="classroom-name">Classroom Name:</label>
                        <input type="text" class="form-control" id="classroom-name" name="classroom-name" required>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="create-classroom-form">Create</button>
                </div>
            </div>
        </div>
    </div>
</div>