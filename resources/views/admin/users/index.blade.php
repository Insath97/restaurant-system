@extends('admin.layouts.master')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <h2>User Management</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">User Management</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">System Admin Users</h5>
                    <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="fas fa-plus me-2"></i>Add New User
                    </button>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="userTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <span class="badge bg-primary-custom">
                                                {{ $user->getRoleNames()->first() }}
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary-custom me-1 edit-item"
                                                data-id="{{ $user->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <a href="{{ route('admin.users.destroy', $user->id) }}"
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

    {{-- create model --}}
    @include('admin.users.create')

    {{-- edit model --}}
    @include('admin.users.edit')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            const userTable = $('#userTable').DataTable({
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

            // Handle create form submission
            $('#addUserForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const submitBtn = form.find('button[type="submit"]');
                const spinner = submitBtn.find('.spinner-border');
                const submitText = submitBtn.find('.submit-text');

                submitText.text('Creating...');
                spinner.removeClass('d-none');
                submitBtn.prop('disabled', true);

                // Clear previous errors
                form.find('.is-invalid').removeClass('is-invalid');
                form.find('.invalid-feedback').text('');

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
                                    $('#addUserModal').modal('hide');
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
                        submitText.text('Create User');
                        spinner.addClass('d-none');
                        submitBtn.prop('disabled', false);
                    }
                });
            });

            // Handle edit button click
            $(document).on('click', '.edit-item', function() {
                const userId = $(this).data('id');
                const editUrl = "{{ route('admin.users.update', ':id') }}".replace(':id', userId);

                // Clear previous errors
                $('#editUserForm').find('.is-invalid').removeClass('is-invalid');
                $('#editUserForm').find('.invalid-feedback').text('');

                $.get("{{ route('admin.users.edit', ':id') }}".replace(':id', userId), function(response) {
                    $('#edit_name').val(response.user.name);
                    $('#edit_email').val(response.user.email);
                    $('#edit_role').val(response.user_role);
                    $('#editUserForm').attr('action', editUrl);

                    $('#editUserModal').modal('show');
                });
            });

            // Handle edit form submission
            $('#editUserForm').on('submit', function(e) {
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
                                    $('#editUserModal').modal('hide');
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
                        submitText.text('Update User');
                        spinner.addClass('d-none');
                        submitBtn.prop('disabled', false);
                    }
                });
            });

        });
    </script>
@endpush
