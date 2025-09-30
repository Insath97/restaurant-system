<ul class="pagination justify-content-center">
    <!-- Previous Button -->
    <li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
        <a class="page-link pagination-link" href="#" data-category-id="{{ $category->id }}"
            data-page="{{ $currentPage - 1 }}" tabindex="{{ $currentPage == 1 ? '-1' : '' }}">
            <i class="fas fa-chevron-left"></i>
        </a>
    </li>

    <!-- Page Numbers -->
    @for ($page = 1; $page <= $totalPages; $page++)
        <li class="page-item {{ $page == $currentPage ? 'active' : '' }}">
            <a class="page-link pagination-link" href="#" data-category-id="{{ $category->id }}"
                data-page="{{ $page }}">
                {{ $page }}
            </a>
        </li>
    @endfor

    <!-- Next Button -->
    <li class="page-item {{ $currentPage == $totalPages ? 'disabled' : '' }}">
        <a class="page-link pagination-link" href="#" data-category-id="{{ $category->id }}"
            data-page="{{ $currentPage + 1 }}">
            <i class="fas fa-chevron-right"></i>
        </a>
    </li>
</ul>
