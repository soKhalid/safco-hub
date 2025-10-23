@extends('layouts.app')

@section('title', $media->title)

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Media Preview -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body p-0">
                    @if($media->type == 'image')
                    <img src="{{ $media->file_url }}" class="img-fluid w-100 rounded-3" alt="{{ $media->title }}" id="mediaImage" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#mediaModal">
                    @elseif($media->type == 'video')
                    <video controls class="w-100 rounded-3" style="max-height: 600px;">
                        <source src="{{ $media->file_url }}" type="{{ $media->mime_type }}">
                        Your browser does not support the video tag.
                    </video>
                    @else
                    <div class="text-center p-5 bg-light rounded-3">
                        <i class="bi bi-file-earmark-text" style="font-size: 8rem; color: #9CA3AF;"></i>
                        <h4 class="mt-3">{{ $media->file_name }}</h4>
                        <p class="text-muted">{{ $media->mime_type }}</p>
                        <a href="{{ route('gallery.download', $media) }}" class="btn btn-primary btn-lg mt-3">
                            <i class="bi bi-download"></i> Download Document
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="d-flex gap-2 mt-3">
                <a href="{{ route('gallery.download', $media) }}" class="btn btn-primary flex-grow-1">
                    <i class="bi bi-download"></i> Download
                </a>
                @if($media->type == 'image')
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#mediaModal">
                    <i class="bi bi-arrows-fullscreen"></i> Fullscreen
                </button>
                @endif
                <button type="button" class="btn btn-outline-secondary" onclick="history.back()">
                    <i class="bi bi-arrow-left"></i> Back
                </button>
            </div>
        </div>

        <!-- Media Info -->
        <div class="col-lg-4">
            <div class="card shadow border-0 rounded-3 mb-4">
                <div class="card-body">
                    <h2 class="h4 mb-3">{{ $media->title }}</h2>

                    @if($media->description)
                    <p class="text-muted">{{ $media->description }}</p>
                    @endif

                    <hr>

                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Category</h6>
                        <a href="{{ route('gallery.category', $media->category->slug) }}" class="badge bg-primary text-decoration-none fs-6">
                            {{ $media->category->name }}
                        </a>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Type</h6>
                        <span class="badge bg-secondary fs-6">{{ ucfirst($media->type) }}</span>
                    </div>

                    <hr>

                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <div class="p-3 bg-light rounded">
                                <i class="bi bi-eye text-primary fs-4"></i>
                                <div class="fw-bold mt-2">{{ number_format($media->views) }}</div>
                                <small class="text-muted">Views</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-light rounded">
                                <i class="bi bi-download text-success fs-4"></i>
                                <div class="fw-bold mt-2">{{ number_format($media->downloads) }}</div>
                                <small class="text-muted">Downloads</small>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="small text-muted">
                        <div class="d-flex justify-content-between mb-2">
                            <span>File Size:</span>
                            <strong>{{ $media->formatted_file_size }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>File Name:</span>
                            <strong class="text-truncate ms-2" style="max-width: 200px;">{{ $media->file_name }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Uploaded By:</span>
                            <strong>{{ $media->user->name }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Upload Date:</span>
                            <strong>{{ $media->created_at->format('M d, Y') }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Media -->
    @if($relatedMedia->count() > 0)
    <div class="mt-5">
        <h3 class="mb-4">Related Media</h3>
        <div class="row g-4">
            @foreach($relatedMedia as $item)
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
    </div>
    @endif
</div>

<!-- Fullscreen Modal for Images -->
@if($media->type == 'image')
<div class="modal fade" id="mediaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content bg-dark">
            <div class="modal-header border-0">
                <h5 class="modal-title text-white">{{ $media->title }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex align-items-center justify-content-center p-0">
                <img src="{{ $media->file_url }}" class="img-fluid" alt="{{ $media->title }}" style="max-height: 90vh;">
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <a href="{{ route('gallery.download', $media) }}" class="btn btn-primary">
                    <i class="bi bi-download"></i> Download
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
    #mediaImage:hover {
        opacity: 0.9;
    }

    .modal-fullscreen .modal-body {
        background: rgba(0, 0, 0, 0.95);
    }
</style>
@endpush
