<div class="modal fade" id="addPermissionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addPermissionForm" action="{{ route('admin.permissions.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Permission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="group_name" class="form-label">Group Name *</label>
                        <input type="text" class="form-control @error('group_name') is-invalid @enderror"
                            id="group_name" name="group_name" value="{{ old('group_name') }}" required>
                        <div class="invalid-feedback" id="group_nameError"></div>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Permission Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name') }}" required>
                        <div class="invalid-feedback" id="nameError"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-custom">
                        <span class="submit-text">Save Permission</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
