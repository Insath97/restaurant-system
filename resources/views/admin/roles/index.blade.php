@extends('admin.layouts.master')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <h2>Roles Management</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Roles Management</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">System Roles</h5>
                    <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                        <i class="fas fa-plus me-2"></i>Add New Role
                    </button>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="table-1">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Role Name</th>
                                    <th>Permission</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $role->name }}</td>
                                        <td class="w-50">
                                            @if ($role->name === 'Super Admin')
                                                <span class="badge bg-info">All Permissions</span>
                                            @else
                                                @foreach ($role->permissions as $permission)
                                                    <span
                                                        class="badge bg-primary-custom mb-1">{{ $permission->name }}</span>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td class="action-buttons">
                                            @if ($role->name != 'Super Admin')
                                                <button class="btn btn-sm btn-primary-custom me-1 edit-role"
                                                    data-id="{{ $role->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <a href="{{ route('admin.roles.destroy', $role->id) }}"
                                                    class="btn btn-danger delete-item">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            @endif
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

    <!-- Add Role Modal (keep your existing modal code) -->
    @include('admin.roles.create')

    <!-- Edit Role Modal (keep your existing modal code) -->
    @include('admin.roles.edit')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#table-1').DataTable({
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

            // Group select all functionality
            $(document).on('change', '.group-select-all', function() {
                const group = $(this).data('group');
                $(`.permission-checkbox[data-group="${group}"]`).prop('checked', $(this).is(':checked'));
            });

            // Handle permission checkbox change to update group select all
            $(document).on('change', '.permission-checkbox', function() {
                const group = $(this).data('group');
                const groupCheckboxes = $(`.permission-checkbox[data-group="${group}"]`);
                const groupSelectAll = $(`.group-select-all[data-group="${group}"]`);

                groupSelectAll.prop('checked',
                    groupCheckboxes.length === groupCheckboxes.filter(':checked').length
                );
            });

            // Handle create form submission
            $('#addRoleForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const submitBtn = form.find('button[type="submit"]');
                const spinner = submitBtn.find('.spinner-border');
                const submitText = submitBtn.find('.submit-text');

                submitText.text('Creating...');
                spinner.removeClass('d-none');
                submitBtn.prop('disabled', true);

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                text: response.message,
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didClose: () => {
                                    $('#addRoleModal').modal('hide');
                                    form.trigger('reset');
                                    window.location.reload();
                                }
                            });
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            $.each(errors, function(field, messages) {
                                const input = form.find('[name="' + field + '"]');
                                input.addClass('is-invalid');
                                input.next('.invalid-feedback').text(messages[0])
                                    .show();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                text: xhr.responseJSON.message || 'An error occurred',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                            });
                        }
                    },
                    complete: function() {
                        submitText.text('Create Role');
                        spinner.addClass('d-none');
                        submitBtn.prop('disabled', false);
                    }
                });
            });

            // Edit role modal handling
            $(document).on('click', '.edit-role', function() {
                const roleId = $(this).data('id');
                const editUrl = "{{ route('admin.roles.update', ':id') }}".replace(':id', roleId);

                $.get("{{ route('admin.roles.edit', ':id') }}".replace(':id', roleId), function(data) {
                    $('#edit_role_name').val(data.role.name);
                    $('#editRoleForm').attr('action', editUrl);

                    // Build permissions groups for edit modal
                    let permissionsHtml = '';
                    data.permissionGroups.forEach(group => {
                        permissionsHtml += `
                    <div class="card mb-3">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">${group.name}</h6>
                            <div class="form-check form-switch">
                                <input class="form-check-input group-select-all" type="checkbox"
                                    data-group="${group.slug}">
                                <label class="form-check-label small">Select All</label>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">`;

                        group.permissions.forEach(permission => {
                            const isChecked = data.rolePermissions.includes(
                                permission.id) ? 'checked' : '';
                            permissionsHtml += `
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input permission-checkbox"
                                            type="checkbox"
                                            name="permissions[]"
                                            value="${permission.name}"
                                            id="edit_perm_${permission.id}"
                                            data-group="${group.slug}"
                                            ${isChecked}>
                                        <label class="form-check-label" for="edit_perm_${permission.id}">
                                            ${permission.name}
                                        </label>
                                    </div>
                                </div>`;
                        });

                        permissionsHtml += `
                            </div>
                        </div>
                    </div>`;
                    });

                    $('#editPermissionGroups').html(permissionsHtml);

                    // Update select all toggles
                    $('.group-select-all').each(function() {
                        const group = $(this).data('group');
                        const groupCheckboxes = $(
                            `.permission-checkbox[data-group="${group}"]`);
                        $(this).prop('checked',
                            groupCheckboxes.length === groupCheckboxes.filter(
                                ':checked').length
                        );
                    });

                    $('#editRoleModal').modal('show');
                });
            });

            // Handle edit form submission
            $('#editRoleForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const submitBtn = form.find('button[type="submit"]');
                const spinner = submitBtn.find('.spinner-border');
                const submitText = submitBtn.find('.submit-text');

                submitText.text('Updating...');
                spinner.removeClass('d-none');
                submitBtn.prop('disabled', true);

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize() + '&_method=PUT',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                text: response.message,
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didClose: () => {
                                    $('#editRoleModal').modal('hide');
                                    window.location.reload();
                                }
                            });
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            $.each(errors, function(field, messages) {
                                const input = form.find('[name="' + field + '"]');
                                input.addClass('is-invalid');
                                input.next('.invalid-feedback').text(messages[0])
                                    .show();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                text: xhr.responseJSON.message || 'An error occurred',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                            });
                        }
                    },
                    complete: function() {
                        submitText.text('Update Role');
                        spinner.addClass('d-none');
                        submitBtn.prop('disabled', false);
                    }
                });
            });

        });
    </script>
@endpush
