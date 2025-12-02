@extends('admin.layouts.master')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <h2>Customer Reviews</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Customer Reviews</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">All Reviews</h5>
                    <div class="d-flex">
                        <div class="input-group me-2" style="width: 250px;">
                            <input type="text" id="searchInput" class="form-control"
                                placeholder="Search reviews...">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="reviewsTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Review Title</th>
                                    <th>Customer</th>
                                    <th>Type</th>
                                    <th>Rating</th>
                                    <th>Status</th>
                                    <th>Featured</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reviews as $review)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <strong>{{ $review->review_title }}</strong>
                                                <small class="text-muted mt-1">{{ \Illuminate\Support\Str::limit($review->comment, 80) }}</small>
                                                @if($review->reviewable)
                                                    <small class="text-muted mt-1">
                                                        {{ $review->review_type }} #{{ $review->reviewable_reference }}
                                                    </small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2">
                                                    <div class="avatar-title bg-primary rounded">
                                                        {{ substr($review->user->name, 0, 1) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $review->user->name }}</h6>
                                                    <small class="text-muted">{{ $review->user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $review->reviewable_type === 'App\Models\Order' ? 'success' : 'info' }}">
                                                {{ $review->review_type }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rating-stars me-2">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $review->rating)
                                                            <i class="fas fa-star text-warning"></i>
                                                        @else
                                                            <i class="far fa-star text-warning"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <span class="badge bg-secondary">{{ $review->rating }}/5</span>
                                            </div>
                                            @if($review->reviewable_type === 'App\Models\Order' && $review->food_quality)
                                                <small class="text-muted d-block mt-1">
                                                    Food: {{ $review->food_quality }}/5
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($review->is_approved)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle me-1"></i> Approved
                                                </span>
                                            @else
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-clock me-1"></i> Pending
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($review->is_featured)
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-star me-1"></i> Featured
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="far fa-star me-1"></i> Regular
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ $review->created_at->format('M d, Y') }}</small>
                                            <br>
                                            <small class="text-muted">{{ $review->created_at->format('g:i A') }}</small>
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

        .rating-stars {
            font-size: 14px;
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
            $('#reviewsTable').DataTable({
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
                    [0, 'desc'] // Sort by first column (ID) descending
                ]
            });

            // Remove the manual search input functionality since DataTable has built-in search
            $('#searchInput').on('keyup', function() {
                $('#reviewsTable').DataTable().search(this.value).draw();
            });
        });
    </script>
@endpush
