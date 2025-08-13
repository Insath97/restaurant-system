<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editUserForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Name *</label>
                        <input type="text" class="form-control" id="edit_name" name="name">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="edit_email" name="email">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="edit_password" name="password">
                        <div class="invalid-feedback"></div>
                        <small class="text-muted">Leave blank to keep current password</small>
                    </div>
                    <div class="mb-3">
                        <label for="edit_password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="edit_password_confirmation"
                            name="password_confirmation">
                    </div>
                    <div class="mb-3">
                        <label for="edit_role" class="form-label">Role *</label>
                        <select class="form-select" id="edit_role" name="role">
                            <option value="">Select Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-custom">
                        <span class="submit-text">Update User</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
