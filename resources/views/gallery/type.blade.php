@extends('layouts.app')

@section('title', ucfirst($type) . 's')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="text-center animate__animated animate__fadeIn">
            <h1 class="hero-title">
                @if($type == 'image')
                <i class="bi bi-image"></i> Images
                @elseif($type == 'video')
                <i class="bi bi-play-circle"></i> Videos
                @else
                <i class="bi bi-file-earmark-text"></i> Documents
                @endif
            </h1>
            <p class="hero-subtitle">Browse all {{ $type }}s in our collection</p>

            <!-- Search Box -->
            <div class="row justify-content-center mt-4">
                <div class="col-md-8 col-lg-6">
                    <form action="{{ route('gallery.search') }}" method="GET" class="search-box">
                        <div class="input-group">
                            <input type="text" name="q" class="form-control form-control-lg" placeholder="Search media..." value="{{ request('q') }}">
                            <button class="btn btn-primary px-4" type="submit">
                                <i class="bi bi-search"></i> Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container">
    <!-- Type Filter -->
    <div class="filter-tabs animate__animated animate__fadeInUp mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Filter by Type</h5>
            <div>
                <select class="form-select form-select-sm" id="sortSelect" onchange="location = this.value;">
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'latest']) }}" {{ request('sort') == 'latest' || !request('sort') ? 'selected' : '' }}>Latest</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'popular']) }}" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'downloads']) }}" {{ request('sort') == 'downloads' ? 'selected' : '' }}>Most Downloaded</option>
                </select>
            </div>
        </div>

        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('gallery.index') }}">
                    All Media
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $type == 'image' ? 'active' : '' }}" href="{{ route('gallery.type', 'image') }}">
                    <i class="bi bi-image"></i> Images
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $type == 'video' ? 'active' : '' }}" href="{{ route('gallery.type', 'video') }}">
                    <i class="bi bi-play-circle"></i> Videos
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $type == 'document' ? 'active' : '' }}" href="{{ route('gallery.type', 'document') }}">
                    <i class="bi bi-file-earmark-text"></i> Documents
                </a>
            </li>
        </ul>
    </div>

    <!-- Categories Filter -->
    <div class="filter-tabs animate__animated animate__fadeInUp">
        <h5 class="mb-3">Filter by Category</h5>

        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link {{ !request('category') ? 'active' : '' }}" href="{{ route('gallery.type', $type) }}">
                    All Categories
                </a>
            </li>
            @foreach($categories as $category)
            <li class="nav-item">
                <a class="nav-link {{ request('category') == $category->slug ? 'active' : '' }}" href="{{ route('gallery.type', $type) }}?category={{ $category->slug }}">
                    @if($category->icon)
                    <i class="bi bi-{{ $category->icon }}"></i>
                    @endif
                    {{ $category->name }}
                </a>
            </li>
            @endforeach
        </ul>
    </div>

    <!-- Media Grid -->
    <section class="animate__animated animate__fadeInUp mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">All {{ ucfirst($type) }}s</h3>
            <p class="text-muted mb-0">{{ $media->total() }} items found</p>
        </div>

        @if($media->count() > 0)
        <div class="row g-4 mb-4">
            @foreach($media as $item)
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card media-card">
                    @if($item->type == 'image')
                    <img src="{{ $item->file_url }}" class="card-img-top" alt="{{ $item->title }}">
                    @elseif($item->type == 'video')
                    <div class="media-icon bg-dark text-white">
                        <i class="bi bi-play-circle"></i>
                    </div>
                    @else
                    <div class="media-icon bg-light text-secondary">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    @endif

                    <div class="card-body">
                        <h5 class="media-title">{{ Str::limit($item->title, 35) }}</h5>

                        <div class="d-flex gap-2 mb-3">
                            <span class="badge bg-secondary">{{ $item->category->name }}</span>
                            @if($item->is_featured)
                            <span class="badge bg-warning"><i class="bi bi-star-fill"></i></span>
                            @endif
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('gallery.show', $item->id) }}" class="btn btn-sm btn-primary flex-grow-1">
                                <i class="bi bi-eye"></i> View
                            </a>
                            <a href="{{ route('gallery.download', $item) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-download"></i>
                            </a>
                        </div>

                        <div class="media-meta">
                            <span><i class="bi bi-eye"></i> {{ number_format($item->views) }}</span>
                            <span>{{ $item->formatted_file_size }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $media->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-inbox" style="font-size: 5rem; color: #ccc;"></i>
            <h4 class="mt-3">No {{ $type }}s found</h4>
            <p class="text-muted">No {{ $type }}s have been uploaded yet</p>
            <a href="{{ route('gallery.index') }}" class="btn btn-primary mt-3">
                <i class="bi bi-arrow-left"></i> Back to All Media
            </a>
        </div>
        @endif
    </section>
</div>
@endsection
