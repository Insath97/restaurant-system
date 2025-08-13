@extends('admin.layouts.master')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <h2>Permission Management</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Permission Management</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">System Permissions</h5>
                    <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addPermissionModal">
                        <i class="fas fa-plus me-2"></i>Add New Permission
                    </button>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="permissionsTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Group Name</th>
                                    <th>Permission Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($permissions as $permission)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $permission->group_name }}</td>
                                        <td>{{ $permission->name }}</td>
                                        <td class="action-buttons">
                                            <button class="btn btn-sm btn-primary-custom me-1 edit-item"
                                                data-id="{{ $permission->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <a href="{{ route('admin.permissions.destroy', $permission->id) }}"
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

    @include('admin.permission.create')
    @include('admin.permission.edit')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#permissionsTable').DataTable({
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

            // Handle edit button click
            $('.edit-item').click(function() {
                const permissionId = $(this).data('id');
                const editUrl = "{{ route('admin.permissions.update', ':id') }}".replace(':id',
                    permissionId);

                // Clear previous errors
                $('#editPermissionForm').find('.invalid-feedback').text('');
                $('#editPermissionForm').find('.is-invalid').removeClass('is-invalid');

                // Fetch permission data
                $.ajax({
                    url: "{{ route('admin.permissions.edit', ':id') }}".replace(':id',
                        permissionId),
                    type: 'GET',
                    success: function(response) {
                        $('#edit_group_name').val(response.group_name);
                        $('#edit_name').val(response.name);
                        $('#editPermissionForm').attr('action', editUrl);

                        // Show modal
                        new bootstrap.Modal(document.getElementById('editPermissionModal'))
                            .show();
                    },
                    error: function(xhr) {
                        console.error('Error fetching permission data:', xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            text: 'Failed to load permission data',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    }
                });
            });

            // Handle create form submission
            $('#addPermissionForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const formData = new FormData(this);
                const submitBtn = form.find('button[type="submit"]');
                const spinner = submitBtn.find('.spinner-border');
                const submitText = submitBtn.find('.submit-text');

                // Show loading state
                submitText.text('Saving...');
                spinner.removeClass('d-none');
                submitBtn.prop('disabled', true);

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
                            // Reset button state
                            spinner.addClass('d-none');
                            submitText.text('Save Permission');
                            submitBtn.prop('disabled', false);

                            // Show success toast
                            Swal.fire({
                                icon: 'success',
                                text: response.message ||
                                    'Permission created successfully',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didClose: () => {
                                    $('#addPermissionModal').modal('hide');
                                    form.trigger('reset');
                                    window.location.reload();
                                }
                            });
                        }
                    },
                    error: function(xhr) {
                        // Reset button state
                        spinner.addClass('d-none');
                        submitText.text('Save Permission');
                        submitBtn.prop('disabled', false);

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
                            $('#addPermissionModal').modal('show');
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
            $('#editPermissionForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const formData = new FormData(this);
                const submitBtn = form.find('button[type="submit"]');
                const spinner = submitBtn.find('.spinner-border');
                const submitText = submitBtn.find('.submit-text');

                // Show loading state
                submitText.text('Updating...');
                spinner.removeClass('d-none');
                submitBtn.prop('disabled', true);

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
                            // Reset button state
                            spinner.addClass('d-none');
                            submitText.text('Update Permission');
                            submitBtn.prop('disabled', false);

                            // Show success toast
                            Swal.fire({
                                icon: 'success',
                                text: response.message ||
                                    'Permission updated successfully',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didClose: () => {
                                    $('#editPermissionModal').modal('hide');
                                    window.location.reload();
                                }
                            });
                        }
                    },
                    error: function(xhr) {
                        // Reset button state
                        spinner.addClass('d-none');
                        submitText.text('Update Permission');
                        submitBtn.prop('disabled', false);

                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;

                            // Display errors under each field
                            $.each(errors, function(field, messages) {
                                const input = form.find('[name="' + field + '"]');
                                const inputGroup = input.closest('.input-group');
                                const errorContainer = form.find('.' + field + '-error')
                                    .length ?
                                    form.find('.' + field + '-error') :
                                    input.closest('.mb-3').find('.invalid-feedback');

                                // Add error icon and styling
                                inputGroup.addClass('has-validation');
                                input.addClass('is-invalid');

                                if (errorContainer.length) {
                                    errorContainer.text(messages[0]).show();
                                } else {
                                    input.after('<div class="invalid-feedback">' +
                                        messages[0] + '</div>');
                                }
                            });

                            // Keep modal open
                            $('#editPermissionModal').modal('show');
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
            $('#addPermissionModal, #editPermissionModal').on('hidden.bs.modal', function() {
                const form = $(this).find('form');
                form.trigger('reset');
                form.find('.is-invalid').removeClass('is-invalid');
                form.find('.invalid-feedback').text('').hide();
                form.find('.spinner-border').addClass('d-none');
                form.find('.submit-text').text($(this).attr('id') === 'addPermissionModal' ?
                    'Save Permission' :
                    'Update Permission');
                form.find('button[type="submit"]').prop('disabled', false);
            });

        });
    </script>
@endpush
