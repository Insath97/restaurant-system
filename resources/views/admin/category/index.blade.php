@extends('admin.layouts.master')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <h2>Category Management</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Category Management</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Food Categories</h5>
                    <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                        <i class="fas fa-plus me-2"></i>Add New Category
                    </button>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="categoriesTable" class="table table-admin table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Description</th>
                                    <th width="15%">Created Date</th>
                                    <th width="15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($category as $category)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><strong>{{ $category->name }}</strong></td>
                                        <td>{{ $category->code }}</td>
                                        <td>{{ $category->description ?? '-' }}</td>
                                        <td>{{ $category->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-primary-custom me-1 edit-btn"
                                                data-id="{{ $category->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <a href="{{ route('admin.categories.destroy', $category->id) }}"
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

    {{-- add category model --}}
    @include('admin.category.create')
    {{-- edit category model --}}
    @include('admin.category.edit')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize all admin tables with consistent settings
            $('.table-admin').each(function() {
                let tableId = $(this).attr('id');

                $('#' + tableId).DataTable({
                    responsive: true,
                    dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                    lengthMenu: [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, "All"]
                    ],
                    pageLength: 10,
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search...",
                        lengthMenu: "", // Remove default text
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                        infoEmpty: "Showing 0 to 0 of 0 entries",
                        infoFiltered: "(filtered from _MAX_ total entries)",
                        paginate: {
                            first: "First",
                            last: "Last",
                            next: '<i class="fas fa-chevron-right"></i>',
                            previous: '<i class="fas fa-chevron-left"></i>'
                        }
                    },
                    initComplete: function() {
                        // Custom length menu styling
                        $('.dataTables_length').html(`
                <div class="d-flex align-items-center">
                    <span class="me-2">Show</span>
                    <select class="form-select form-select-sm" style="width: 85px; margin: 0 4px">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="-1">All</option>
                    </select>
                    <span class="ms-1">entries</span>
                </div>
              `);

                        // Style search input
                        $('.dataTables_filter input').addClass('form-control form-control-sm');

                        // Initialize the custom select functionality
                        $('.dataTables_length select').change(function() {
                            var table = $('#' + tableId).DataTable();
                            table.page.len($(this).val()).draw();
                        });
                    }
                });
            });

            // Handle edit button click
            $('.edit-btn').click(function() {
                var categoryId = $(this).data('id');
                var editUrl = "{{ route('admin.categories.update', ':id') }}".replace(':id', categoryId);

                // Clear previous errors
                $('.invalid-feedback').text('');
                $('.is-invalid').removeClass('is-invalid');

                // Fetch category data
                $.ajax({
                    url: "{{ route('admin.categories.edit', ':id') }}".replace(':id', categoryId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        $('#edit_name').val(response.name);
                        $('#edit_code').val(response.code);
                        $('#edit_description').val(response.description);
                        $('#editCategoryForm').attr('action', editUrl);

                        // Show modal
                        var editModal = new bootstrap.Modal(document.getElementById(
                            'editCategoryModal'));
                        editModal.show();
                    },
                    error: function(xhr) {
                        console.error('Error fetching category data:', xhr.responseText);
                        alert('Failed to load category data. Please try again.');
                    }
                });
            });

            // Handle create form submission
            $('#addCategoryForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const formData = new FormData(this);
                const submitBtn = form.find('button[type="submit"]');

                // Create and show spinner
                const spinner = $(
                    '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>'
                    );
                submitBtn.prepend(spinner);
                submitBtn.prop('disabled', true);
                submitBtn.find('.btn-text').text('Saving...');

                // Clear previous errors
                form.find('.is-invalid').removeClass('is-invalid');
                form.find('.invalid-feedback').text('');

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Remove spinner and reset button
                            spinner.remove();
                            submitBtn.prop('disabled', false);
                            submitBtn.find('.btn-text').text('Save Category');

                            // Show success toast
                            Swal.fire({
                                icon: 'success',
                                text: response.message ||
                                    'Category created successfully',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didClose: () => {
                                    $('#addCategoryModal').modal('hide');
                                    form.trigger('reset');
                                    window.location.reload();
                                }
                            });
                        }
                    },
                    error: function(xhr) {
                        // Remove spinner and reset button
                        spinner.remove();
                        submitBtn.prop('disabled', false);
                        submitBtn.find('.btn-text').text('Save Category');

                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;

                            // Display errors under each field
                            $.each(errors, function(field, messages) {
                                const input = form.find('[name="' + field + '"]');
                                const errorContainer = input.closest('.mb-3').find(
                                    '.invalid-feedback');

                                input.addClass('is-invalid');
                                if (errorContainer.length) {
                                    errorContainer.text(messages[0]).show();
                                } else {
                                    input.after('<div class="invalid-feedback">' +
                                        messages[0] + '</div>');
                                }
                            });

                            // Keep modal open
                            $('#addCategoryModal').modal('show');
                        } else {
                            let errorMsg = 'An error occurred';
                            try {
                                const response = JSON.parse(xhr.responseText);
                                if (response.message) {
                                    errorMsg = response.message;
                                }
                            } catch (e) {
                                console.error('Error parsing response:', e);
                            }

                            Swal.fire({
                                icon: 'error',
                                text: errorMsg,
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            });
                        }
                    }
                });
            });

            // Handle edit form submission
            $('#editCategoryForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const formData = new FormData(this);
                const submitBtn = form.find('button[type="submit"]');

                // Create and show spinner
                const spinner = $(
                    '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>'
                    );
                submitBtn.prepend(spinner);
                submitBtn.prop('disabled', true);
                submitBtn.find('.btn-text').text('Updating...');

                // Clear previous errors
                form.find('.is-invalid').removeClass('is-invalid');
                form.find('.invalid-feedback').text('');

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Remove spinner and reset button
                            spinner.remove();
                            submitBtn.prop('disabled', false);
                            submitBtn.find('.btn-text').text('Update Category');

                            // Show success toast
                            Swal.fire({
                                icon: 'success',
                                text: response.message ||
                                    'Category updated successfully',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didClose: () => {
                                    $('#editCategoryModal').modal('hide');
                                    window.location.reload();
                                }
                            });
                        }
                    },
                    error: function(xhr) {
                        // Remove spinner and reset button
                        spinner.remove();
                        submitBtn.prop('disabled', false);
                        submitBtn.find('.btn-text').text('Update Category');

                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;

                            // Display errors under each field
                            $.each(errors, function(field, messages) {
                                const input = form.find('[name="' + field + '"]');
                                const errorContainer = input.closest('.mb-3').find(
                                    '.invalid-feedback');

                                input.addClass('is-invalid');
                                if (errorContainer.length) {
                                    errorContainer.text(messages[0]).show();
                                } else {
                                    input.after('<div class="invalid-feedback">' +
                                        messages[0] + '</div>');
                                }
                            });

                            // Keep modal open
                            $('#editCategoryModal').modal('show');
                        } else {
                            let errorMsg = 'An error occurred';
                            try {
                                const response = JSON.parse(xhr.responseText);
                                if (response.message) {
                                    errorMsg = response.message;
                                }
                            } catch (e) {
                                console.error('Error parsing response:', e);
                            }

                            Swal.fire({
                                icon: 'error',
                                text: errorMsg,
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            });
                        }
                    }
                });
            });

            // Reset form when modal is closed
            $('#addCategoryModal, #editCategoryModal').on('hidden.bs.modal', function() {
                const form = $(this).find('form');
                form.trigger('reset');
                form.find('.is-invalid').removeClass('is-invalid');
                form.find('.invalid-feedback').text('').hide();
                const submitBtn = form.find('button[type="submit"]');
                submitBtn.find('.spinner-border').remove();
                submitBtn.find('.btn-text').text($(this).attr('id') === 'addCategoryModal' ?
                    'Save Category' : 'Update Category');
                submitBtn.prop('disabled', false);
            });

        });
    </script>
@endpush
