<div class="modal fade" id="addMenuItemModal" tabindex="-1" aria-labelledby="addMenuItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="menuItemForm" method="POST" action="{{ route('admin.menus.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addMenuItemModalLabel">Add New Menu Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="itemName" class="form-label">Item Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="itemName" name="name" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="itemCode" class="form-label">Item Code *</label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror"
                                    id="itemCode" name="code" required>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="itemCategory" class="form-label">Category *</label>
                                <select class="form-select @error('category_id') is-invalid @enderror" id="itemCategory"
                                    name="category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="itemPrice" class="form-label">Price (Rs.) *</label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror"
                                    id="itemPrice" name="price" min="0" step="50" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="itemImage" class="form-label">Item Image</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror"
                                    id="itemImage" name="image" accept="image/*">
                                <small class="text-muted">Recommended size: 800x600 pixels (Max 2MB)</small>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="mt-2 image-preview-container">
                                    <img id="imagePreview" src="#" alt="Image Preview"
                                        class="img-thumbnail d-none" style="max-height: 150px;">
                                </div>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="isFeatured" name="is_featured">
                                <label class="form-check-label" for="isFeatured">Featured Item</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="itemDescription" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="itemDescription" name="description"
                            rows="3"></textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-custom">Save Item</button>
                </div>
            </form>
        </div>
    </div>
</div>
