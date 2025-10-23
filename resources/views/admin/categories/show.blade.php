@extends('layouts.admin')

@section('title', $category->name)

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">
                @if($category->icon)
                <i class="bi bi-{{ $category->icon }}"></i>
                @endif
                {{ $category->name }}
            </h1>
            <p class="page-subtitle">Category details and media</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Edit Category
            </a>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Category Info -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Category Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="text-muted mb-2">Name</h6>
                    <p class="mb-0">{{ $category->name }}</p>
                </div>

                <div class="mb-3">
                    <h6 class="text-muted mb-2">Slug</h6>
                    <p class="mb-0"><code>{{ $category->slug }}</code></p>
                </div>

                @if($category->description)
                <div class="mb-3">
                    <h6 class="text-muted mb-2">Description</h6>
                    <p class="mb-0">{{ $category->description }}</p>
                </div>
                @endif

                @if($category->icon)
                <div class="mb-3">
                    <h6 class="text-muted mb-2">Icon</h6>
                    <p class="mb-0">
                        <i class="bi bi-{{ $category->icon }}" style="font-size: 2rem;"></i>
                        <code class="ms-2">{{ $category->icon }}</code>
                    </p>
                </div>
                @endif

                <div class="mb-3">
                    <h6 class="text-muted mb-2">Order</h6>
                    <p class="mb-0">{{ $category->order }}</p>
                </div>

                <div class="mb-3">
                    <h6 class="text-muted mb-2">Status</h6>
                    @if($category->is_active)
                    <span class="badge bg-success">Active</span>
                    @else
                    <span class="badge bg-danger">Inactive</span>
                    @endif
                </div>

                <div class="mb-3">
                    <h6 class="text-muted mb-2">Total Media</h6>
                    <p class="mb-0">
                        <span class="badge bg-primary fs-6">{{ $category->media_count }} items</span>
                    </p>
                </div>

                <div class="mb-3">
                    <h6 class="text-muted mb-2">Created</h6>
                    <p class="mb-0">{{ $category->created_at->format('M d, Y h:i A') }}</p>
                </div>

                <div>
                    <h6 class="text-muted mb-2">Last Updated</h6>
                    <p class="mb-0">{{ $category->updated_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Media -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Media in this Category ({{ $media->total() }})</h5>
                <a href="{{ route('admin.media.create') }}?category_id={{ $category->id }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Media
                </a>
            </div>
            <div class="card-body">
                @if($media->count() > 0)
                <div class="row g-3">
                    @foreach($media as $item)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm">
                            @if($item->type == 'image')
                            <img src="{{ $item->file_url }}" class="card-img-top" alt="{{ $item->title }}" style="height: 150px; object-fit: cover;">
                            @elseif($item->type == 'video')
                            <div class="bg-dark d-flex align-items-center justify-content-center" style="height: 150px;">
                                <i class="bi bi-play-circle text-white" style="font-size: 3rem;"></i>
                            </div>
                            @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                <i class="bi bi-file-earmark-text text-muted" style="font-size: 3rem;"></i>
                            </div>
                            @endif

                            <div class="card-body">
                                <h6 class="card-title mb-2">{{ Str::limit($item->title, 30) }}</h6>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="text-muted">
                                        <span class="badge bg-secondary">{{ ucfirst($item->type) }}</span>
                                    </small>
                                    <small class="text-muted">{{ $item->formatted_file_size }}</small>
                                </div>
                                <div class="btn-group btn-group-sm w-100" role="group">
                                    <a href="{{ route('admin.media.show', $item) }}" class="btn btn-outline-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.media.edit', $item) }}" class="btn btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-3">
                    {{ $media->links() }}
                </div>
                @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                    <h5 class="mt-3">No media in this category</h5>
                    <p class="text-muted">Upload some media to get started</p>
                    <a href="{{ route('admin.media.create') }}" class="btn btn-primary mt-2">
                        <i class="bi bi-cloud-upload"></i> Upload Media
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
