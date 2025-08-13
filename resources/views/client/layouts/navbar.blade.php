<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="#">
            <span style="color: var(--golden-yellow); font-family: 'Playfair Display', serif; font-size: 1.5rem;">Dynamic
                Family Restaurant</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('index') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('about') }}">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#menu">Menu</a>
                </li>


                <li class="nav-item">
                    <a class="nav-link" href="#gallery">Gallery</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contact">Contact</a>
                </li>
                <button class="btn btn-danger rounded-pill text-white ms-3">
                    Reserve Table
                </button>
            </ul>
        </div>
    </div>
</nav>
