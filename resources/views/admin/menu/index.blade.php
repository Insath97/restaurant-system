@extends('admin.layouts.master')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <h2>Menu Management</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Menu Management</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Menu Items</h5>
                    <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addMenuItemModal">
                        <i class="fas fa-plus me-2"></i>Add New Food
                    </button>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="menusTable" class="table table-admin table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Description</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Featured</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($menus as $menu)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <img src="{{ asset($menu->image) }}" alt="{{ $menu->name }}"
                                                class="menu-item-img"
                                                onerror="this.src='{{ asset('images/default-food.png') }}'">
                                        </td>
                                        <td>{{ $menu->name }}</td>
                                        <td>{{ $menu->code }}</td>
                                        <td>{{ Str::limit($menu->description, 50) }}</td>
                                        <td>{{ $menu->category->name }}</td>
                                        <td>Rs. {{ number_format($menu->price, 2) }}</td>
                                        <td>
                                            @if ($menu->is_featured)
                                                <span class="badge bg-success">Yes</span>
                                            @else
                                                <span class="badge bg-danger">No</span>
                                            @endif
                                        </td>
                                        <td class="action-buttons">
                                            <button class="btn btn-sm btn-info-custom view-item"
                                                data-id="{{ $menu->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary-custom me-1 edit-item"
                                                data-id="{{ $menu->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <a href="{{ route('admin.menus.destroy', $menu->id) }}"
                                                class="btn btn-sm btn-danger text-center delete-item">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.menu.create')
    @include('admin.menu.edit')
    @include('admin.menu.view')
@endsection


@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#menusTable').DataTable({
                responsive: true,
                dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                pageLength: 10,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search...",
                    paginate: {
                        next: '<i class="fas fa-chevron-right"></i>',
                        previous: '<i class="fas fa-chevron-left"></i>'
                    }
                }
            });

            // Image preview for create modal
            $('#itemImage').change(function() {
                previewImage(this, '#imagePreview');
            });

            // Image preview for edit modal
            $('#edit_itemImage').change(function() {
                previewImage(this, '#edit_imagePreview');
            });

            function previewImage(input, previewId) {
                const file = input.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $(previewId).attr('src', e.target.result).removeClass('d-none');
                    }
                    reader.readAsDataURL(file);
                }
            }

            // Handle edit button click
            $('.edit-item').click(function() {
                const itemId = $(this).data('id');
                const editUrl = "{{ route('admin.menus.update', ':id') }}".replace(':id', itemId);

                // Clear previous errors
                $('.invalid-feedback').text('');
                $('.is-invalid').removeClass('is-invalid');

                // Fetch menu item data
                $.ajax({
                    url: "{{ route('admin.menus.edit', ':id') }}".replace(':id', itemId),
                    type: 'GET',
                    success: function(response) {
                        if (response.status) {
                            const menu = response.menu;

                            $('#edit_itemName').val(menu.name);
                            $('#edit_itemCode').val(menu.code);
                            $('#edit_itemCategory').val(menu.category_id);
                            $('#edit_itemPrice').val(menu.price);
                            $('#edit_itemDescription').val(menu.description);
                            $('#edit_isFeatured').prop('checked', menu.is_featured);

                            // Set image preview
                            if (menu.image) {
                                $('#edit_imagePreview').attr('src', "{{ asset('') }}" +
                                        menu.image)
                                    .removeClass('d-none');
                            }

                            // Set form action
                            $('#editMenuItemForm').attr('action', editUrl);

                            // Show modal
                            new bootstrap.Modal(document.getElementById('editMenuItemModal'))
                                .show();
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr.responseText);
                        toastr.error('Failed to load menu item data');
                    }
                });
            });


        });

        // Handle view button click
        $(document).on('click', '.view-item', function() {
            const itemId = $(this).data('id');

            /*  // Show loading state if needed
             $('#viewItemModal').find('.modal-body').html(
                 '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>'
                 ); */

            // Initialize modal first
            const viewModal = new bootstrap.Modal(document.getElementById('viewItemModal'));
            viewModal.show();

            // Then load data
            $.ajax({
                url: "{{ route('admin.menus.show', ':id') }}".replace(':id', itemId),
                type: 'GET',
                success: function(response) {
                    if (response.status) {
                        const menu = response.data;

                        $('#view_itemName').text(menu.name);
                        $('#view_itemCode').text(menu.code);
                        $('#view_itemCategory').text(menu.category.name);
                        $('#view_itemPrice').text('Rs. ' + menu.price);
                        $('#view_itemDescription').text(menu.description || 'No description available');

                        // Set featured status
                        const featuredBadge = $('#view_isFeatured');
                        if (menu.is_featured) {
                            featuredBadge.text('Yes').removeClass('bg-danger').addClass('bg-success');
                        } else {
                            featuredBadge.text('No').removeClass('bg-success').addClass('bg-danger');
                        }

                        // Set image
                        const imgElement = $('#view_itemImage');
                        if (menu.image) {
                            imgElement.attr('src', "{{ asset('') }}" + menu.image);
                        } else {
                            imgElement.attr('src', "https://placehold.co/400");
                        }
                    }
                },
                error: function(xhr) {
                    console.error('Error:', xhr.responseText);
                    toastr.error('Failed to load menu item details');
                    viewModal.hide();
                }
            });
        });
    </script>
@endpush
