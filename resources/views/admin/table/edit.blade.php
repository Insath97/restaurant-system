<div class="modal fade" id="editTableModal" tabindex="-1" aria-labelledby="editTableModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editTableForm" method="POST" action="" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editTableModalLabel">Edit Table</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_code" class="form-label">Table Code *</label>
                        <div class="input-group has-validation">
                            <input type="text" class="form-control" id="edit_code" name="code" required>
                            <div class="invalid-feedback code-error"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Table Name *</label>
                        <div class="input-group has-validation">
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                            <div class="invalid-feedback name-error"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_capacity" class="form-label">Capacity *</label>
                        <div class="input-group has-validation">
                            <input type="number" class="form-control" id="edit_capacity" name="capacity" min="1" required>
                            <div class="invalid-feedback capacity-error"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <div class="input-group has-validation">
                            <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                            <div class="invalid-feedback description-error"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-custom">
                        <span class="submit-text">Update Table</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
