<div class="modal fade" id="editMenuItemModal" tabindex="-1" aria-labelledby="editMenuItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="editMenuItemForm" action="" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editMenuItemModalLabel">Edit Menu Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_itemName" class="form-label">Item Name *</label>
                                <input type="text" class="form-control" id="edit_itemName" name="name" required>
                                <div class="invalid-feedback name-error"></div>
                            </div>
                            <div class="mb-3">
                                <label for="edit_itemCode" class="form-label">Item Code *</label>
                                <input type="text" class="form-control" id="edit_itemCode" name="code" required>
                                <div class="invalid-feedback code-error"></div>
                            </div>
                            <div class="mb-3">
                                <label for="edit_itemCategory" class="form-label">Category *</label>
                                <select class="form-select" id="edit_itemCategory" name="category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback category-error"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_itemPrice" class="form-label">Price (Rs.) *</label>
                                <input type="number" class="form-control" id="edit_itemPrice" name="price"
                                       min="0" step="0.01" required>
                                <div class="invalid-feedback price-error"></div>
                            </div>
                            <div class="mb-3">
                                <label for="edit_itemImage" class="form-label">Item Image</label>
                                <input type="file" class="form-control" id="edit_itemImage"
                                       name="image" accept="image/*">
                                <small class="text-muted">Max 2MB (JPEG, PNG, JPG, GIF, WEBP)</small>
                                <div class="invalid-feedback image-error"></div>
                                <div class="mt-2">
                                    <img id="edit_imagePreview" src="#" alt="Image Preview"
                                         class="img-thumbnail d-none" style="max-height: 150px;">
                                </div>
                            </div>
                            <div class="mb-3 form-check form-switch">
                                <input type="checkbox" class="form-check-input" id="edit_isFeatured" name="is_featured">
                                <label class="form-check-label" for="edit_isFeatured">Featured Item</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_itemDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_itemDescription" name="description" rows="3"></textarea>
                        <div class="invalid-feedback description-error"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-custom">Update Item</button>
                </div>
            </form>
        </div>
    </div>
</div>
