@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <h1 class="page-title">Dashboard</h1>
    <p class="page-subtitle">Welcome back, {{ auth()->user()->name }}! Here's what's happening with your media hub.</p>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Media</div>
                    <div class="stat-value">{{ number_format($stats['total_media']) }}</div>
                </div>
                <i class="bi bi-file-earmark-image stat-icon"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card success">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Views</div>
                    <div class="stat-value">{{ number_format($stats['total_views']) }}</div>
                </div>
                <i class="bi bi-eye stat-icon"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card warning">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Downloads</div>
                    <div class="stat-value">{{ number_format($stats['total_downloads']) }}</div>
                </div>
                <i class="bi bi-download stat-icon"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card info">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Storage Used</div>
                    <div class="stat-value" style="font-size: 1.5rem;">{{ $stats['storage_used'] }}</div>
                </div>
                <i class="bi bi-hdd stat-icon"></i>
            </div>
        </div>
    </div>
</div>

<!-- Media Type Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-image text-primary" style="font-size: 3rem;"></i>
                <h3 class="mt-3 mb-0">{{ number_format($stats['total_images']) }}</h3>
                <p class="text-muted mb-0">Images</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-play-circle text-success" style="font-size: 3rem;"></i>
                <h3 class="mt-3 mb-0">{{ number_format($stats['total_videos']) }}</h3>
                <p class="text-muted mb-0">Videos</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-file-earmark-text text-warning" style="font-size: 3rem;"></i>
                <h3 class="mt-3 mb-0">{{ number_format($stats['total_documents']) }}</h3>
                <p class="text-muted mb-0">Documents</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Media -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Media</h5>
                <a href="{{ route('admin.media.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Media</th>
                                <th>Type</th>
                                <th>Category</th>
                                <th>Views</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentMedia as $media)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($media->type == 'image')
                                            <i class="bi bi-image text-primary me-2"></i>
                                        @elseif($media->type == 'video')
                                            <i class="bi bi-play-circle text-success me-2"></i>
                                        @else
                                            <i class="bi bi-file-earmark-text text-warning me-2"></i>
                                        @endif
                                        <span>{{ Str::limit($media->title, 30) }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ ucfirst($media->type) }}</span>
                                </td>
                                <td>{{ $media->category->name }}</td>
                                <td>{{ number_format($media->views) }}</td>
                                <td>{{ $media->created_at->format('M d, Y') }}</td>
                                <td>
                                    @if($media->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No media uploaded yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Popular Media & Categories -->
    <div class="col-lg-4">
        <!-- Popular Media -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Most Popular</h5>
            </div>
            <div class="card-body">
                @forelse($popularMedia as $media)
                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <div class="flex-grow-1">
                        <div class="fw-medium">{{ Str::limit($media->title, 25) }}</div>
                        <small class="text-muted">
                            <i class="bi bi-eye"></i> {{ number_format($media->views) }} views
                        </small>
                    </div>
                    @if($media->type == 'image')
                        <i class="bi bi-image text-primary"></i>
                    @elseif($media->type == 'video')
                        <i class="bi bi-play-circle text-success"></i>
                    @else
                        <i class="bi bi-file-earmark-text text-warning"></i>
                    @endif
                </div>
                @empty
                <p class="text-center text-muted">No data available</p>
                @endforelse
            </div>
        </div>

        <!-- Categories -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Categories</h5>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-sm btn-outline-primary">Manage</a>
            </div>
            <div class="card-body">
                @forelse($mediaByCategory as $category)
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <div class="fw-medium">{{ $category->name }}</div>
                        <small class="text-muted">{{ $category->media_count }} items</small>
                    </div>
                    <span class="badge bg-primary rounded-pill">{{ $category->media_count }}</span>
                </div>
                @empty
                <p class="text-center text-muted">No categories yet</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // You can add Chart.js here for visualizations
    console.log('Dashboard loaded');
</script>
@endpush
