@extends('layouts.admin')

@section('title', $media->title)

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Media Details</h1>
            <p class="page-subtitle">{{ $media->title }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.media.edit', $media) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('admin.media.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Media Preview -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-body p-0">
                @if($media->type == 'image')
                <img src="{{ $media->file_url }}" class="img-fluid w-100" alt="{{ $media->title }}">
                @elseif($media->type == 'video')
                <video controls class="w-100" style="max-height: 600px;">
                    <source src="{{ $media->file_url }}" type="{{ $media->mime_type }}">
                    Your browser does not support the video tag.
                </video>
                @else
                <div class="text-center p-5 bg-light">
                    <i class="bi bi-file-earmark-text" style="font-size: 8rem; color: #9CA3AF;"></i>
                    <h4 class="mt-3">{{ $media->file_name }}</h4>
                    <p class="text-muted">{{ $media->mime_type }}</p>
                    <a href="{{ $media->file_url }}" class="btn btn-primary mt-3" download>
                        <i class="bi bi-download"></i> Download Document
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="card mt-3">
            <div class="card-body">
                <div class="d-flex gap-2">
                    <a href="{{ $media->file_url }}" class="btn btn-outline-primary" download>
                        <i class="bi bi-download"></i> Download File
                    </a>
                    <a href="{{ route('gallery.show', $media->id) }}" class="btn btn-outline-info" target="_blank">
                        <i class="bi bi-eye"></i> View in Gallery
                    </a>
                    <form action="{{ route('admin.media.toggle-featured', $media) }}" method="POST" class="d-inline">
                        @csrf
                        @if($media->is_featured)
                        <button type="submit" class="btn btn-outline-warning">
                            <i class="bi bi-star-fill"></i> Unmark Featured
                        </button>
                        @else
                        <button type="submit" class="btn btn-outline-warning">
                            <i class="bi bi-star"></i> Mark as Featured
                        </button>
                        @endif
                    </form>
                    <form action="{{ route('admin.media.toggle-active', $media) }}" method="POST" class="d-inline">
                        @csrf
                        @if($media->is_active)
                        <button type="submit" class="btn btn-outline-secondary">
                            <i class="bi bi-eye-slash"></i> Deactivate
                        </button>
                        @else
                        <button type="submit" class="btn btn-outline-success">
                            <i class="bi bi-eye"></i> Activate
                        </button>
                        @endif
                    </form>
                    <form action="{{ route('admin.media.destroy', $media) }}" method="POST" class="d-inline ms-auto" onsubmit="return confirm('Are you sure you want to delete this media?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Media Info -->
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Media Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="text-muted mb-2">Title</h6>
                    <p class="mb-0">{{ $media->title }}</p>
                </div>

                @if($media->description)
                <div class="mb-3">
                    <h6 class="text-muted mb-2">Description</h6>
                    <p class="mb-0">{{ $media->description }}</p>
                </div>
                @endif

                <div class="mb-3">
                    <h6 class="text-muted mb-2">Category</h6>
                    <p class="mb-0">
                        <a href="{{ route('admin.categories.show', $media->category) }}" class="badge bg-primary text-decoration-none">
                            {{ $media->category->name }}
                        </a>
                    </p>
                </div>

                <div class="mb-3">
                    <h6 class="text-muted mb-2">Type</h6>
                    <span class="badge bg-secondary">{{ ucfirst($media->type) }}</span>
                </div>

                <div class="mb-3">
                    <h6 class="text-muted mb-2">Status</h6>
                    <div class="d-flex gap-2">
                        @if($media->is_active)
                        <span class="badge bg-success">Active</span>
                        @else
                        <span class="badge bg-danger">Inactive</span>
                        @endif

                        @if($media->is_featured)
                        <span class="badge bg-warning">Featured</span>
                        @endif
                    </div>
                </div>

                <hr>

                <div class="mb-3">
                    <h6 class="text-muted mb-2">File Name</h6>
                    <p class="mb-0 small text-break">{{ $media->file_name }}</p>
                </div>

                <div class="mb-3">
                    <h6 class="text-muted mb-2">File Size</h6>
                    <p class="mb-0">{{ $media->formatted_file_size }}</p>
                </div>

                <div class="mb-3">
                    <h6 class="text-muted mb-2">MIME Type</h6>
                    <p class="mb-0"><code>{{ $media->mime_type }}</code></p>
                </div>

                <hr>

                <div class="mb-3">
                    <h6 class="text-muted mb-2">Uploaded By</h6>
                    <p class="mb-0">{{ $media->user->name }}</p>
                </div>

                <div class="mb-3">
                    <h6 class="text-muted mb-2">Upload Date</h6>
                    <p class="mb-0">{{ $media->created_at->format('M d, Y h:i A') }}</p>
                </div>

                <div class="mb-3">
                    <h6 class="text-muted mb-2">Last Updated</h6>
                    <p class="mb-0">{{ $media->updated_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="p-3 bg-light rounded">
                            <i class="bi bi-eye text-primary" style="font-size: 2rem;"></i>
                            <h4 class="mt-2 mb-0">{{ number_format($media->views) }}</h4>
                            <small class="text-muted">Views</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="p-3 bg-light rounded">
                            <i class="bi bi-download text-success" style="font-size: 2rem;"></i>
                            <h4 class="mt-2 mb-0">{{ number_format($media->downloads) }}</h4>
                            <small class="text-muted">Downloads</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
