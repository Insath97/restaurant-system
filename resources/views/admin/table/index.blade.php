@extends('admin.layouts.master')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <h2>Table Management</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Table Management</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Restaurant Tables</h5>
                    <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addTableModal">
                        <i class="fas fa-plus me-2"></i>Add New Table
                    </button>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tablesTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Table Name</th>
                                    <th>Capacity</th>
                                    <th>Status</th>
                                    <th>Availability</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tables as $table)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $table->name }}</td>
                                        <td>{{ $table->capacity }} guests</td>
                                        <td>
                                            <span class="badge bg-{{ $table->statusColor() }}">
                                                {{ ucfirst($table->status) }}
                                            </span>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input availability-toggle" type="checkbox"
                                                    data-id="{{ $table->id }}" id="toggle-{{ $table->id }}"
                                                    {{ $table->is_available ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        </td>
                                        <td class="action-buttons">
                                            <button class="btn btn-sm btn-primary-custom me-1 edit-item"
                                                data-id="{{ $table->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <a href="{{ route('admin.tables.destroy', $table->id) }}"
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

    @include('admin.table.create')
    @include('admin.table.edit')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#tablesTable').DataTable({
                responsive: true
            });

            $('#addTableModal').on('show.bs.modal', function() {});

            // Clear validation errors when modal is hidden
            $('#addTableModal').on('hidden.bs.modal', function() {
                $(this).find('form')[0].reset();
                $(this).find('.is-invalid').removeClass('is-invalid');
                $(this).find('.invalid-feedback').remove();
            });
        });

        $('.edit-item').click(function() {
            var tableId = $(this).data('id');
            var editUrl = "{{ route('admin.tables.update', ':id') }}".replace(':id', tableId);

            // Fetch table data
            $.ajax({
                url: "{{ route('admin.tables.edit', ':id') }}".replace(':id', tableId),
                type: 'GET',
                success: function(response) {
                    $('#edit_name').val(response.name);
                    $('#edit_code').val(response.code);
                    $('#edit_capacity').val(response.capacity);
                    $('#edit_description').val(response.description);
                    $('#editTableForm').attr('action', editUrl);

                    // Show modal
                    var editModal = new bootstrap.Modal(document.getElementById('editTableModal'));
                    editModal.show();
                }
            });
        });

        $(document).on('change', '.availability-toggle', function() {
            const checkbox = $(this);
            const isAvailable = checkbox.is(':checked');

            $.ajax({
                url: '/admin/tables/' + checkbox.data('id') + '/toggle-availability',
                method: 'PATCH',
                data: {
                    _token: '{{ csrf_token() }}',
                    is_available: isAvailable
                },
                success: function(response) {
                    if (response.success) {
                        // Update checkbox to match server response
                        checkbox.prop('checked', response.is_available);

                        // Show SweetAlert success notification
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message || 'Availability updated successfully',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    } else {
                        // Revert checkbox and show error
                        checkbox.prop('checked', !isAvailable);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'Failed to update availability',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    }
                },
                error: function(xhr) {
                    // Revert checkbox on error
                    checkbox.prop('checked', !isAvailable);

                    let errorMsg = 'Error updating availability';
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
                        title: 'Error',
                        text: errorMsg,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                }
            });
        });
    </script>
@endpush
