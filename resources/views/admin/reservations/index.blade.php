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
                                    <th>Reservation code</th>
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
                                        <td>{{ $reservation->code }}</td>
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
            $(document).on('click', '.change-status', function(e) {
                e.preventDefault();
                const reservationId = $(this).data('reservation-id');
                const currentStatus = $(this).data('current-status');

                // Store reservation ID in the form
                $('#changeStatusForm').data('reservation-id', reservationId);
                $('#statusSelect').val(currentStatus);

                new bootstrap.Modal(document.getElementById('statusModal')).show();
            });

            // Handle status change form submission WITHOUT page reload
            $('#changeStatusForm').on('submit', function(e) {
                e.preventDefault();

                const form = $(this);
                const reservationId = form.data('reservation-id');
                const newStatus = $('#statusSelect').val();
                const submitBtn = form.find('button[type="submit"]');
                const modal = bootstrap.Modal.getInstance(document.getElementById('statusModal'));

                // Validate reservation ID
                if (!reservationId) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Reservation ID is missing',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                    return;
                }

                // Show loading state
                submitBtn.prop('disabled', true);
                submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Updating...');

                console.log('Updating status for reservation:', reservationId, 'to:', newStatus);

                // FIXED: Use proper route construction with the ID
                const url = "{{ route('admin.reservations.update-status', ':id') }}".replace(':id',
                    reservationId);

                console.log('AJAX URL:', url); // Debug log

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'PUT',
                        status: newStatus
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log('Status update response:', response);

                        if (response.success) {
                            // Update status badge in table
                            const statusBadge = $(
                                `.reservation-status[data-reservation-id="${reservationId}"]`
                                );
                            statusBadge
                                .removeClass(
                                    'bg-warning bg-success bg-danger bg-info bg-secondary')
                                .addClass('bg-' + getStatusColor(response.status))
                                .text(response.status.charAt(0).toUpperCase() + response.status
                                    .slice(1));

                            // Update dropdown current status
                            $(`[data-reservation-id="${reservationId}"] .change-status`)
                                .data('current-status', response.status);

                            // Hide modal
                            modal.hide();

                            // Show success message
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            });
                        } else {
                            throw new Error(response.message || 'Failed to update status');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Status update error:', xhr.responseText);

                        let errorMsg = 'Failed to update status';
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.message) {
                                errorMsg = response.message;
                            }
                            if (response.errors && response.errors.status) {
                                errorMsg = response.errors.status[0];
                            }
                        } catch (e) {
                            console.error('Error parsing response:', e);
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMsg,
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    },
                    complete: function() {
                        // Reset button state
                        submitBtn.prop('disabled', false);
                        submitBtn.html('<i class="fas fa-save me-2"></i>Update Status');
                    }
                });
            });

            // View details modal with order information
            $(document).on('click', '.view-details', function(e) {
                e.preventDefault();
                const reservationId = $(this).data('reservation-id');

                console.log('Reservation ID:', reservationId);
                console.log('Route URL:', "{{ route('admin.reservations.details', ':id') }}".replace(':id',
                    reservationId));

                // Show loading state
                $('#detailsModal .modal-body').html(`
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2">Loading reservation details...</p>
                </div>
            `);

                new bootstrap.Modal(document.getElementById('detailsModal')).show();

                // Load reservation details via AJAX
                $.ajax({
                    url: "{{ route('admin.reservations.details', ':id') }}".replace(':id',
                        reservationId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        console.log('AJAX Success:', response);
                        if (response.success) {
                            const reservation = response.reservation;
                            let orderHtml = '';

                            if (response.has_order && reservation.order) {
                                const order = reservation.order;
                                orderHtml = `
                                <div class="card mt-4">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0"><i class="fas fa-shopping-bag me-2"></i>Order Details</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <strong>Order Number:</strong> ${order.order_number}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Order Status:</strong>
                                                <span class="badge bg-${getOrderStatusColor(order.status)}">
                                                    ${order.status.charAt(0).toUpperCase() + order.status.slice(1)}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <strong>Total Amount:</strong> Rs. ${parseFloat(order.total).toFixed(2)}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Payment Method:</strong> ${order.payment_method.replace(/_/g, ' ').toUpperCase()}
                                            </div>
                                        </div>

                                        <h6 class="mt-4 mb-3">Order Items:</h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Item</th>
                                                        <th>Price</th>
                                                        <th>Qty</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                            `;

                                // Add order items
                                order.items.forEach(item => {
                                    orderHtml += `
                                    <tr>
                                        <td>${item.menu_name}</td>
                                        <td>Rs. ${parseFloat(item.price).toFixed(2)}</td>
                                        <td>${item.quantity}</td>
                                        <td>Rs. ${parseFloat(item.total).toFixed(2)}</td>
                                    </tr>
                                `;
                                });

                                orderHtml += `
                                                </tbody>
                                                <tfoot class="table-light">
                                                    <tr>
                                                        <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                                        <td><strong>Rs. ${parseFloat(order.subtotal).toFixed(2)}</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3" class="text-end"><strong>Service Charge:</strong></td>
                                                        <td><strong>Rs. ${parseFloat(order.service_charge).toFixed(2)}</strong></td>
                                                    </tr>
                                                    ${order.delivery_fee > 0 ? `
                                                                    <tr>
                                                                        <td colspan="3" class="text-end"><strong>Delivery Fee:</strong></td>
                                                                        <td><strong>Rs. ${parseFloat(order.delivery_fee).toFixed(2)}</strong></td>
                                                                    </tr>
                                                                ` : ''}
                                                    <tr>
                                                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                                        <td><strong>Rs. ${parseFloat(order.total).toFixed(2)}</strong></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            `;
                            } else {
                                orderHtml = `
                                <div class="alert alert-info mt-4">
                                    <i class="fas fa-info-circle me-2"></i>
                                    No order found for this reservation.
                                </div>
                            `;
                            }

                            const detailsHtml = `
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-header bg-primary text-white">
                                            <h6 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Reservation Information</h6>
                                        </div>
                                        <div class="card-body">
                                            <p><strong>Reservation Code:</strong> ${reservation.code}</p>
                                            <p><strong>Status:</strong>
                                                <span class="badge bg-${getStatusColor(reservation.status)}">
                                                    ${reservation.status.charAt(0).toUpperCase() + reservation.status.slice(1)}
                                                </span>
                                            </p>
                                            <p><strong>Date:</strong> ${new Date(reservation.reservation_date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })}</p>
                                            <p><strong>Time:</strong> ${new Date('1970-01-01T' + reservation.reservation_time + 'Z').toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true })}</p>
                                            <p><strong>Guests:</strong> ${reservation.guest_count} people</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-header bg-info text-white">
                                            <h6 class="mb-0"><i class="fas fa-users me-2"></i>Customer & Table Details</h6>
                                        </div>
                                        <div class="card-body">
                                            <p><strong>Customer:</strong> ${reservation.user.name}</p>
                                            <p><strong>Email:</strong> ${reservation.user.email}</p>
                                            <p><strong>Table:</strong> ${reservation.table.name} (${reservation.table.code})</p>
                                            <p><strong>Table Capacity:</strong> ${reservation.table.capacity} people</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            ${reservation.special_requests ? `
                                            <div class="card mb-3">
                                                <div class="card-header bg-warning text-dark">
                                                    <h6 class="mb-0"><i class="fas fa-sticky-note me-2"></i>Special Requests</h6>
                                                </div>
                                                <div class="card-body">
                                                    <p class="mb-0">${reservation.special_requests}</p>
                                                </div>
                                            </div>
                                        ` : ''}

                            ${orderHtml}
                        `;

                            $('#detailsModal .modal-body').html(detailsHtml);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('AJAX Error:', xhr, status, error);
                        let errorMsg = 'Failed to load reservation details';
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.message) {
                                errorMsg = response.message;
                            }
                        } catch (e) {
                            console.error('Error parsing response:', e);
                        }

                        $('#detailsModal .modal-body').html(`
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            ${errorMsg}
                        </div>
                    `);
                    }
                });
            });

            // Helper function for reservation status colors
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

            // Helper function for order status colors
            function getOrderStatusColor(status) {
                switch (status) {
                    case 'pending':
                        return 'warning';
                    case 'confirmed':
                        return 'info';
                    case 'preparing':
                        return 'primary';
                    case 'ready':
                        return 'success';
                    case 'completed':
                        return 'secondary';
                    case 'cancelled':
                        return 'danger';
                    default:
                        return 'secondary';
                }
            }

        });
    </script>
@endpush
