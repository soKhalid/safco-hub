@extends('layouts.admin')

@section('title', 'Edit Media')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Edit Media</h1>
            <p class="page-subtitle">Update media information</p>
        </div>
        <a href="{{ route('admin.media.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Media
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.media.update', $media) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Category Selection -->
                    <div class="mb-4">
                        <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="category_id" id="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $media->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Title -->
                    <div class="mb-4">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $media->title) }}" required>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $media->description) }}</textarea>
                    </div>

                    <!-- Current File Info -->
                    <div class="mb-4">
                        <label class="form-label">Current File</label>
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        @if($media->type == 'image')
                                        <img src="{{ $media->file_url }}" class="img-fluid rounded" alt="{{ $media->title }}">
                                        @elseif($media->type == 'video')
                                        <div class="bg-dark d-flex align-items-center justify-content-center rounded" style="height: 100px;">
                                            <i class="bi bi-play-circle text-white" style="font-size: 3rem;"></i>
                                        </div>
                                        @else
                                        <div class="bg-white d-flex align-items-center justify-content-center rounded" style="height: 100px;">
                                            <i class="bi bi-file-earmark-text text-muted" style="font-size: 3rem;"></i>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="col-md-9">
                                        <p class="mb-1"><strong>Filename:</strong> {{ $media->file_name }}</p>
                                        <p class="mb-1"><strong>Type:</strong> <span class="badge bg-secondary">{{ ucfirst($media->type) }}</span></p>
                                        <p class="mb-1"><strong>Size:</strong> {{ $media->formatted_file_size }}</p>
                                        <p class="mb-0"><strong>Uploaded:</strong> {{ $media->created_at->format('M d, Y h:i A') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Options -->
                    <div class="mb-4">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ $media->is_featured ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                Mark as Featured
                            </label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ $media->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-save"></i> Update Media
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
