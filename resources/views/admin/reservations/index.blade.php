@extends('admin.layouts.master')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <h2>Reservation Management</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Reservation Management</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">All Reservations</h5>
                    <div class="d-flex">
                        <div class="input-group me-2" style="width: 250px;">
                            <input type="text" id="searchInput" class="form-control"
                                placeholder="Search reservations...">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="reservationsTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Reservation ID</th>
                                    <th>Customer</th>
                                    <th>Table</th>
                                    <th>Date & Time</th>
                                    <th>Guests</th>
                                    <th>Status</th>
                                    <th>Special Requests</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reservations as $reservation)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>#RSV-{{ $reservation->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2">
                                                    <div class="avatar-title bg-primary rounded">
                                                        {{ substr($reservation->user->name, 0, 1) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $reservation->user->name }}</h6>
                                                    <small class="text-muted">{{ $reservation->user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong>{{ $reservation->table->name }}</strong>
                                            <br>
                                            <small class="text-muted">({{ $reservation->table->code }})</small>
                                        </td>
                                        <td>
                                            <strong>{{ $reservation->reservation_date->format('M d, Y') }}</strong>
                                            <br>
                                            <small
                                                class="text-muted">{{ \Carbon\Carbon::parse($reservation->reservation_time)->format('g:i A') }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $reservation->guest_count }} guests</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $reservation->statusColor() }} reservation-status"
                                                data-reservation-id="{{ $reservation->id }}">
                                                {{ ucfirst($reservation->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($reservation->special_requests)
                                                <button class="btn btn-sm btn-outline-info view-requests"
                                                    data-requests="{{ $reservation->special_requests }}">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                            @else
                                                <span class="text-muted">None</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ $reservation->created_at->format('M d, Y g:i A') }}</small>
                                        </td>
                                        <td class="action-buttons">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-primary-custom dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown">
                                                    <i class="fas fa-cog"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item change-status" href="#"
                                                            data-reservation-id="{{ $reservation->id }}"
                                                            data-current-status="{{ $reservation->status }}">
                                                            <i class="fas fa-sync-alt me-2"></i>Change Status
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item view-details" href="#"
                                                            data-reservation-id="{{ $reservation->id }}">
                                                            <i class="fas fa-eye me-2"></i>View Details
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
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

    @include('admin.reservations.status-modal')
    @include('admin.reservations.details-modal')
    @include('admin.reservations.requests-modal')
@endsection

@push('styles')
    <style>
        .avatar-sm {
            width: 32px;
            height: 32px;
        }

        .avatar-title {
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .reservation-card {
            border-left: 4px solid #007bff;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-confirmed {
            background-color: #d1edff;
            color: #0c5460;
        }

        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }

        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#reservationsTable').DataTable({
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
                },
                order: [
                    [0, 'desc']
                ]
            });

            // View special requests
            $(document).on('click', '.view-requests', function() {
                const requests = $(this).data('requests');
                $('#specialRequestsContent').text(requests);
                new bootstrap.Modal(document.getElementById('requestsModal')).show();
            });

            // Change status modal
            $(document).on('click', '.change-status', function() {
                const reservationId = $(this).data('reservation-id');
                const currentStatus = $(this).data('current-status');

                $('#changeStatusForm').attr('action',
                    "{{ route('admin.reservations.update-status', ':id') }}".replace(':id',
                        reservationId));
                $('#statusSelect').val(currentStatus);

                new bootstrap.Modal(document.getElementById('statusModal')).show();
            });

            // View details modal
            $(document).on('click', '.view-details', function() {
                const reservationId = $(this).data('reservation-id');

                // Show loading state
                $('#detailsModal .modal-body').html(`
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2">Loading reservation details...</p>
                    </div>
                `);

                new bootstrap.Modal(document.getElementById('detailsModal')).show();

                // Load reservation details (you can implement this via AJAX if needed)
                // For now, we'll just show basic info
                setTimeout(() => {
                    $('#detailsModal .modal-body').html(`
                        <div class="reservation-card p-3 mb-3">
                            <h5>Reservation Details</h5>
                            <p><strong>ID:</strong> #RSV-${reservationId}</p>
                            <p><strong>Status:</strong> <span class="badge bg-primary">Loading...</span></p>
                        </div>
                        <p class="text-muted">Detailed view implementation would go here.</p>
                    `);
                }, 1000);
            });

            // Handle status change form submission
            $('#changeStatusForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const formData = form.serialize();
                const submitBtn = form.find('button[type="submit"]');
                const reservationId = form.attr('action').split('/').pop();

                // Show loading state
                submitBtn.prop('disabled', true);
                submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Updating...');

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Update status badge in table
                            $(`.reservation-status[data-reservation-id="${reservationId}"]`)
                                .removeClass('bg-warning bg-success bg-danger bg-info')
                                .addClass('bg-' + getStatusColor(response.status))
                                .text(response.status.charAt(0).toUpperCase() + response.status
                                    .slice(1));

                            // Update dropdown current status
                            $(`[data-reservation-id="${reservationId}"] .change-status`)
                                .data('current-status', response.status);

                            // Hide modal
                            bootstrap.Modal.getInstance(document.getElementById('statusModal'))
                                .hide();

                            // Show success message
                            Swal.fire({
                                icon: 'success',
                                text: response.message,
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Failed to update status';
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.message) {
                                errorMsg = response.message;
                            }
                        } catch (e) {
                            console.error('Error:', e);
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
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false);
                        submitBtn.html('<i class="fas fa-save me-2"></i>Update Status');
                    }
                });
            });

            function getStatusColor(status) {
                switch (status) {
                    case 'pending':
                        return 'warning';
                    case 'confirmed':
                        return 'success';
                    case 'cancelled':
                        return 'danger';
                    case 'completed':
                        return 'info';
                    default:
                        return 'secondary';
                }
            }
        });
    </script>
@endpush
