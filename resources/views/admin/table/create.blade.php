<div class="modal fade" id="addTableModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addTableForm" action="{{ route('admin.tables.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Table</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="code" class="form-label">Table Code *</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror"
                               id="code" name="code" value="{{ old('code') }}" >
                        <div class="invalid-feedback" id="codeError"></div>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Table Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name') }}" >
                        <div class="invalid-feedback" id="nameError"></div>
                    </div>
                    <div class="mb-3">
                        <label for="capacity" class="form-label">Capacity *</label>
                        <input type="number" class="form-control @error('capacity') is-invalid @enderror"
                               id="capacity" name="capacity" min="1" value="{{ old('capacity') }}" >
                        <div class="invalid-feedback" id="capacityError"></div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        <div class="invalid-feedback" id="descriptionError"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-custom">
                        <span class="submit-text">Save Table</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
