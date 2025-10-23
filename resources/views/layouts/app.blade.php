<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Media Gallery') - SAFCO Media Hub</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        :root {
            --primary-color: #667eea;
            --primary-dark: #5568d3;
            --secondary-color: #764ba2;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #F9FAFB;
        }

        /* Navbar */
        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: white !important;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-brand i {
            font-size: 2rem;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            transition: all 0.3s;
        }

        .nav-link:hover {
            color: white !important;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 0.5rem;
        }

        .nav-link.active {
            color: white !important;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 0.5rem;
        }

        .btn-outline-light {
            border-width: 2px;
        }

        .btn-outline-light:hover {
            background: white;
            color: var(--primary-color);
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 4rem 0 3rem;
            margin-bottom: 3rem;
        }

        .hero-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            opacity: 0.9;
        }

        /* Search Box */
        .search-box {
            background: white;
            border-radius: 1rem;
            padding: 0.5rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .search-box input {
            border: none;
            padding: 0.75rem 1rem;
        }

        .search-box input:focus {
            outline: none;
            box-shadow: none;
        }

        .search-box .btn {
            border-radius: 0.75rem;
        }

        /* Filter Tabs */
        .filter-tabs {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .filter-tabs .nav-pills .nav-link {
            color: #6B7280;
            border-radius: 0.75rem;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s;
        }

        .filter-tabs .nav-pills .nav-link:hover {
            background: #F3F4F6;
            color: var(--primary-color);
        }

        .filter-tabs .nav-pills .nav-link.active {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        /* Media Card */
        .media-card {
            border: none;
            border-radius: 1rem;
            overflow: hidden;
            transition: all 0.3s;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            height: 100%;
        }

        .media-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .media-card .card-img-top {
            height: 250px;
            object-fit: cover;
        }

        .media-card .media-icon {
            height: 250px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 5rem;
        }

        .media-card .card-body {
            padding: 1.25rem;
        }

        .media-title {
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: #1F2937;
        }

        .media-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #E5E7EB;
            font-size: 0.875rem;
            color: #6B7280;
        }

        .badge {
            padding: 0.35em 0.75em;
            border-radius: 0.5rem;
            font-weight: 500;
        }

        /* Footer */
        .footer {
            background: #1F2937;
            color: white;
            padding: 3rem 0 1.5rem;
            margin-top: 4rem;
        }

        .footer h5 {
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .footer a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer a:hover {
            color: white;
        }

        /* Modal */
        .modal-content {
            border-radius: 1rem;
            border: none;
        }

        .modal-header {
            border-bottom: 1px solid #E5E7EB;
        }

        .modal-footer {
            border-top: 1px solid #E5E7EB;
        }

        /* Pagination */
        .pagination {
            gap: 0.5rem;
        }

        .page-link {
            border-radius: 0.5rem;
            border: none;
            color: var(--primary-color);
            padding: 0.5rem 1rem;
        }

        .page-link:hover {
            background: var(--primary-color);
            color: white;
        }

        .page-item.active .page-link {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }
    </style>

    @stack('styles')
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-collection-play-fill"></i>
                SAFCO Media Hub
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home', 'gallery.index') ? 'active' : '' }}" href="{{ route('gallery.index') }}">
                            <i class="bi bi-house-door"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('gallery.type', 'image') }}">
                            <i class="bi bi-image"></i> Images
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('gallery.type', 'video') }}">
                            <i class="bi bi-play-circle"></i> Videos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('gallery.type', 'document') }}">
                            <i class="bi bi-file-earmark-text"></i> Documents
                        </a>
                    </li>

                    @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Admin
                        </a>
                    </li>
                    @else
                    <li class="nav-item ms-2">
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    @yield('content')

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5>SAFCO Media Hub</h5>
                    <p class="text-white-50">Your organization's central media repository for images, videos, and documents.</p>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('gallery.index') }}">Home</a></li>
                        <li class="mb-2"><a href="{{ route('gallery.type', 'image') }}">Images</a></li>
                        <li class="mb-2"><a href="{{ route('gallery.type', 'video') }}">Videos</a></li>
                        <li class="mb-2"><a href="{{ route('gallery.type', 'document') }}">Documents</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Categories</h5>
                    <ul class="list-unstyled">
                        @php
                            $footerCategories = \App\Models\Category::active()->ordered()->take(5)->get();
                        @endphp
                        @foreach($footerCategories as $category)
                        <li class="mb-2">
                            <a href="{{ route('gallery.category', $category->slug) }}">{{ $category->name }}</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <hr class="my-4" style="border-color: rgba(255, 255, 255, 0.1);">
            <div class="text-center text-white-50">
                <p class="mb-0">&copy; {{ date('Y') }} SAFCO Media Hub. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    @stack('scripts')
</body>
</html>
