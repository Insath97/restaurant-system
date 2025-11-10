@extends('admin.layouts.master')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <h2>Order Management</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Order Management</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">All Orders</h5>
                    <div class="d-flex">
                        <div class="input-group me-2" style="width: 250px;">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search orders...">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="ordersTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Order Number</th>
                                    <th>Customer</th>
                                    <th>Type</th>
                                    <th>Table</th>
                                    <th>Items</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                    <th>Order Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $order->order_number }}</strong>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2">
                                                    <div class="avatar-title bg-primary rounded">
                                                        {{ substr($order->customer_name, 0, 1) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $order->customer_name }}</h6>
                                                    <small class="text-muted">{{ $order->customer_email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $order->order_type === 'dine_in' ? 'info' : 'warning' }}">
                                                {{ ucfirst(str_replace('_', ' ', $order->order_type)) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($order->reservation && $order->reservation->table)
                                                <strong>{{ $order->reservation->table->name }}</strong>
                                                <br>
                                                <small class="text-muted">({{ $order->reservation->table->code }})</small>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $order->items->sum('quantity') }}
                                                items</span>
                                        </td>
                                        <td>
                                            <strong>Rs. {{ number_format($order->total, 2) }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $order->statusColor() }} order-status"
                                                data-order-id="{{ $order->id }}">
                                                {{ $order->statusText() }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $order->payment_status ? 'success' : 'warning' }}">
                                                {{ $order->payment_status ? 'Paid' : 'Pending' }}
                                            </span>
                                            <br>
                                            <small class="text-muted">{{ $order->paymentMethodText() }}</small>
                                        </td>
                                        <td>
                                            <small>{{ $order->created_at->format('M d, Y g:i A') }}</small>
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
                                                            data-order-id="{{ $order->id }}"
                                                            data-current-status="{{ $order->status }}">
                                                            <i class="fas fa-sync-alt me-2"></i>Change Status
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item view-details" href="#"
                                                            data-order-id="{{ $order->id }}">
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

    @include('admin.order.status-modal')
    @include('admin.order.details-modal')
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

        .order-card {
            border-left: 4px solid #007bff;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#ordersTable').DataTable({
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

            // Change status modal
            $(document).on('click', '.change-status', function() {
                const orderId = $(this).data('order-id');
                const currentStatus = $(this).data('current-status');

                $('#changeStatusForm').attr('action', "{{ route('admin.orders.update-status', ':id') }}"
                    .replace(':id', orderId));
                $('#statusSelect').val(currentStatus);

                new bootstrap.Modal(document.getElementById('statusModal')).show();
            });

            // View details modal
            $(document).on('click', '.view-details', function() {
                const orderId = $(this).data('order-id');

                // Show loading state
                $('#detailsModal .modal-body').html(`
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2">Loading order details...</p>
                    </div>
                `);

                new bootstrap.Modal(document.getElementById('detailsModal')).show();

                // Load order details via AJAX
                $.ajax({
                    url: "{{ route('admin.orders.show', ':id') }}".replace(':id', orderId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status) {
                            const order = response.data;
                            let tableHtml = '';
                            let itemsHtml = '';

                            // Table information
                            if (order.reservation && order.reservation.table) {
                                tableHtml = `
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <strong>Table:</strong> ${order.reservation.table.name} (${order.reservation.table.code})
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Capacity:</strong> ${order.reservation.table.capacity} people
                                        </div>
                                    </div>
                                `;
                            } else {
                                tableHtml = `
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        No table reservation for this order.
                                    </div>
                                `;
                            }

                            // Order items
                            if (order.items && order.items.length > 0) {
                                itemsHtml = `
                                    <h6 class="mt-4 mb-3"><i class="fas fa-utensils me-2"></i>Order Items</h6>
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

                                order.items.forEach(item => {
                                    itemsHtml += `
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset('') }}${item.menu ? item.menu.image : ''}"
                                                         alt="${item.menu_name}"
                                                         class="me-2"
                                                         style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;"
                                                         onerror="this.src='{{ asset('default/dummy_600x400_ffffff_cccccc.png') }}'">
                                                    <div>
                                                        <strong>${item.menu_name}</strong>
                                                        ${item.menu && item.menu.description ? `<br><small class="text-muted">${item.menu.description}</small>` : ''}
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Rs. ${parseFloat(item.price).toFixed(2)}</td>
                                            <td>${item.quantity}</td>
                                            <td><strong>Rs. ${parseFloat(item.total).toFixed(2)}</strong></td>
                                        </tr>
                                    `;
                                });

                                itemsHtml += `
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
                                                    <td colspan="3" class="text-end"><strong>Total Amount:</strong></td>
                                                    <td><strong>Rs. ${parseFloat(order.total).toFixed(2)}</strong></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                `;
                            }

                            const detailsHtml = `
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card mb-3">
                                            <div class="card-header bg-primary text-white">
                                                <h6 class="mb-0"><i class="fas fa-shopping-bag me-2"></i>Order Information</h6>
                                            </div>
                                            <div class="card-body">
                                                <p><strong>Order Number:</strong> ${order.order_number}</p>
                                                <p><strong>Order Type:</strong>
                                                    <span class="badge bg-${order.order_type === 'dine_in' ? 'info' : 'warning'}">
                                                        ${order.order_type.replace('_', ' ').toUpperCase()}
                                                    </span>
                                                </p>
                                                <p><strong>Status:</strong>
                                                    <span class="badge bg-${getOrderStatusColor(order.status)}">
                                                        ${order.status.charAt(0).toUpperCase() + order.status.slice(1)}
                                                    </span>
                                                </p>
                                                <p><strong>Order Date:</strong> ${new Date(order.created_at).toLocaleString()}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="card mb-3">
                                            <div class="card-header bg-info text-white">
                                                <h6 class="mb-0"><i class="fas fa-user me-2"></i>Customer Information</h6>
                                            </div>
                                            <div class="card-body">
                                                <p><strong>Name:</strong> ${order.customer_name}</p>
                                                <p><strong>Email:</strong> ${order.customer_email}</p>
                                                <p><strong>Phone:</strong> ${order.customer_phone}</p>
                                                ${order.delivery_address ? `<p><strong>Delivery Address:</strong> ${order.delivery_address}</p>` : ''}
                                                ${order.special_instructions ? `<p><strong>Special Instructions:</strong> ${order.special_instructions}</p>` : ''}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mb-3">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0"><i class="fas fa-credit-card me-2"></i>Payment Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Payment Method:</strong> ${order.payment_method.replace(/_/g, ' ').toUpperCase()}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Payment Status:</strong>
                                                    <span class="badge bg-${order.payment_status ? 'success' : 'warning'}">
                                                        ${order.payment_status ? 'PAID' : 'PENDING'}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                ${tableHtml}
                                ${itemsHtml}
                            `;

                            $('#detailsModal .modal-body').html(detailsHtml);
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Failed to load order details';
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.message) {
                                errorMsg = response.message;
                            }
                        } catch (e) {
                            console.error('Error:', e);
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

            // Handle status change form submission
            $('#changeStatusForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const formData = form.serialize();
                const submitBtn = form.find('button[type="submit"]');
                const orderId = form.attr('action').split('/').pop();

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
                            $(`.order-status[data-order-id="${orderId}"]`)
                                .removeClass(
                                    'bg-warning bg-success bg-danger bg-info bg-primary bg-secondary'
                                )
                                .addClass('bg-' + getOrderStatusColor(response.status))
                                .text(getOrderStatusText(response.status));

                            // Update dropdown current status
                            $(`[data-order-id="${orderId}"] .change-status`)
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

            function getOrderStatusText(status) {
                switch (status) {
                    case 'pending':
                        return 'Pending';
                    case 'confirmed':
                        return 'Confirmed';
                    case 'preparing':
                        return 'Preparing';
                    case 'ready':
                        return 'Ready';
                    case 'completed':
                        return 'Completed';
                    case 'cancelled':
                        return 'Cancelled';
                    default:
                        return 'Unknown';
                }
            }
        });
    </script>
@endpush
