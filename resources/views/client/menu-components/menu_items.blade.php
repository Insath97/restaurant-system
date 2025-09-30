<div class="row">
    @foreach ($menus as $menu)
        <div class="col-md-6 col-lg-4 fade-in">
            <div class="menu-card">
                <img src="{{ asset($menu->image) }}" alt="{{ $menu->name }}" loading="lazy">
                <div class="menu-card-body">
                    <h3 class="menu-card-title">{{ $menu->name }}</h3>
                    <div class="menu-card-rating">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= floor($menu->rating ?? 4.5))
                                <i class="fas fa-star"></i>
                            @elseif ($i == ceil($menu->rating ?? 4.5) && ($menu->rating ?? 4.5) != floor($menu->rating ?? 4.5))
                                <i class="fas fa-star-half-alt"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                    </div>
                    <p class="mb-3">{{ $menu->description }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="menu-card-price">Rs. {{ number_format($menu->price, 2) }}</span>
                        <button class="btn btn-add-to-cart" data-menu-id="{{ $menu->id }}">Add to Cart</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
