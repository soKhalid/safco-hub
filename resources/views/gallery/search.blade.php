@extends('layouts.app')

@section('title', 'Search Results')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="text-center animate__animated animate__fadeIn">
            <h1 class="hero-title">
                <i class="bi bi-search"></i>
                Search Results
            </h1>
            @if($search)
            <p class="hero-subtitle">Results for: "{{ $search }}"</p>
            @else
            <p class="hero-subtitle">Enter a search term to find media</p>
            @endif

            <!-- Search Box -->
            <div class="row justify-content-center mt-4">
                <div class="col-md-8 col-lg-6">
                    <form action="{{ route('gallery.search') }}" method="GET" class="search-box">
                        <div class="input-group">
                            <input type="text" name="q" class="form-control form-control-lg" placeholder="Search media..." value="{{ $search }}" autofocus>
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
    <!-- Filters -->
    <div class="filter-tabs animate__animated animate__fadeInUp">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Filter by Category</h5>
            <div>
                <a href="{{ route('gallery.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Back to Gallery
                </a>
            </div>
        </div>

        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link {{ !request('category') ? 'active' : '' }}" href="{{ route('gallery.search', ['q' => $search]) }}">
                    All Categories
                </a>
            </li>
            @foreach($categories as $category)
            <li class="nav-item">
                <a class="nav-link {{ request('category') == $category->slug ? 'active' : '' }}" href="{{ route('gallery.search', ['q' => $search, 'category' => $category->slug]) }}">
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
    <section class="animate__animated animate__fadeInUp">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Search Results</h3>
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
                            <span class="badge bg-primary">{{ ucfirst($item->type) }}</span>
                            <span class="badge bg-secondary">{{ $item->category->name }}</span>
                        </div>

                        @if($item->description)
                        <p class="text-muted small mb-3">{{ Str::limit($item->description, 60) }}</p>
                        @endif

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
            {{ $media->appends(['q' => $search])->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-search" style="font-size: 5rem; color: #ccc;"></i>
            <h4 class="mt-3">No results found</h4>
            @if($search)
            <p class="text-muted">No media matching "{{ $search }}" was found</p>
            <p class="text-muted">Try different keywords or browse all media</p>
            @else
            <p class="text-muted">Enter a search term above</p>
            @endif
            <a href="{{ route('gallery.index') }}" class="btn btn-primary mt-3">
                <i class="bi bi-arrow-left"></i> Back to All Media
            </a>
        </div>
        @endif
    </section>
</div>
@endsection
