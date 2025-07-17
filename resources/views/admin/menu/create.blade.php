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
                                    id="itemName" name="name" value="{{ old('name') }}">
                                <div class="invalid-feedback" id="nameError"></div>
                            </div>
                            <div class="mb-3">
                                <label for="itemCode" class="form-label">Item Code *</label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror"
                                    id="itemCode" name="code" value="{{ old('code') }}" >
                                <div class="invalid-feedback" id="codeError"></div>
                            </div>
                            <div class="mb-3">
                                <label for="itemCategory" class="form-label">Category *</label>
                                <select class="form-select @error('category_id') is-invalid @enderror" id="itemCategory"
                                    name="category_id" >
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="categoryError"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="itemPrice" class="form-label">Price (Rs.) *</label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror"
                                    id="itemPrice" name="price" min="0" step="0.01" value="{{ old('price') }}" >
                                <div class="invalid-feedback" id="priceError"></div>
                            </div>
                            <div class="mb-3">
                                <label for="itemImage" class="form-label">Item Image *</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror"
                                    id="itemImage" name="image" accept="image/*">
                                <small class="text-muted">Recommended size: 800x600 pixels (Max 2MB)</small>
                                <div class="invalid-feedback" id="imageError"></div>
                                <div class="mt-2 image-preview-container">
                                    <img id="imagePreview" src="#" alt="Image Preview"
                                        class="img-thumbnail d-none" style="max-height: 150px;">
                                </div>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="isFeatured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                <label class="form-check-label" for="isFeatured">Featured Item</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="itemDescription" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="itemDescription" name="description"
                            rows="3">{{ old('description') }}</textarea>
                        <div class="invalid-feedback" id="descriptionError"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-custom">
                        <span class="submit-text">Save Item</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
