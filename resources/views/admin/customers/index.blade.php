@extends('admin.layouts.master')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <h2>Customer Management</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Customer Management</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Registered Customers</h5>
                    <div class="d-flex">
                        <span class="badge bg-primary-custom me-3">
                            Total Customers: {{ $customers->count() }}
                        </span>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="customersTable" class="table table-admin table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Registered Date</th>
                                    <th>Last Login</th>
                                    <th width="15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customers as $customer)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $customer->name }}</strong>
                                        </td>
                                        <td>{{ $customer->email }}</td>
                                        <td>{{ $customer->created_at->format('M d, Y') }}</td>
                                        <td>
                                            @if ($customer->last_login_at)
                                                {{ \Carbon\Carbon::parse($customer->last_login_at)->format('M d, Y h:i A') }}
                                            @else
                                                <span class="text-muted">Never</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="" class="btn btn-sm btn-primary-custom me-1"
                                                title="View Details">
                                                <i class="fas fa-eye me-1"></i>View More
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
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize customers table
            $('#customersTable').DataTable({
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
                    searchPlaceholder: "Search customers...",
                    lengthMenu: "",
                    info: "Showing _START_ to _END_ of _TOTAL_ customers",
                    infoEmpty: "Showing 0 to 0 of 0 customers",
                    infoFiltered: "(filtered from _MAX_ total customers)",
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
                        $('#customersTable').DataTable().page.len($(this).val()).draw();
                    });
                }
            });
        });
    </script>
@endpush
