<div class="modal fade" id="viewItemModal" tabindex="-1" aria-labelledby="viewItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewItemModalLabel">Menu Item Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5">
                        <div class="text-center mb-4">
                            <img id="view_itemImage" src="#" alt="Item Image"
                                 class="img-fluid rounded" style="max-height: 300px;">
                        </div>
                    </div>
                    <div class="col-md-7">
                        <h4 id="view_itemName" class="mb-3"></h4>
                        <div class="mb-3">
                            <strong>Code:</strong> <span id="view_itemCode" class="text-muted"></span>
                        </div>
                        <div class="mb-3">
                            <strong>Category:</strong> <span id="view_itemCategory" class="text-muted"></span>
                        </div>
                        <div class="mb-3">
                            <strong>Price:</strong> <span id="view_itemPrice" class="text-muted"></span>
                        </div>
                        <div class="mb-3">
                            <strong>Featured:</strong>
                            <span id="view_isFeatured" class="badge"></span>
                        </div>
                        <hr>
                        <h5>Description</h5>
                        <p id="view_itemDescription" class="text-muted"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
