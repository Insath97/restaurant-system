<div class="modal fade" id="editPermissionModal" tabindex="-1" aria-labelledby="editPermissionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editPermissionForm" method="POST" action="" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editPermissionModalLabel">Edit Permission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_group_name" class="form-label">Group Name *</label>
                        <div class="input-group has-validation">
                            <input type="text" class="form-control" id="edit_group_name" name="group_name" required>
                            <div class="invalid-feedback group_name-error"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Permission Name *</label>
                        <div class="input-group has-validation">
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                            <div class="invalid-feedback name-error"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-custom">
                        <span class="submit-text">Update Permission</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
