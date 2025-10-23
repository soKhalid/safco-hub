@extends('layouts.admin')

@section('title', 'Media Management')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Media Library</h1>
            <p class="page-subtitle">Manage all your media files</p>
        </div>
        <a href="{{ route('admin.media.create') }}" class="btn btn-primary">
            <i class="bi bi-cloud-upload"></i> Upload Media
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.media.index') }}" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search media..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="type" class="form-select">
                    <option value="">All Types</option>
                    <option value="image" {{ request('type') == 'image' ? 'selected' : '' }}>Images</option>
                    <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>Videos</option>
                    <option value="document" {{ request('type') == 'document' ? 'selected' : '' }}>Documents</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="category_id" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Media Grid -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Media ({{ $media->total() }})</h5>
        <button type="button" class="btn btn-sm btn-danger" id="bulkDeleteBtn" style="display: none;">
            <i class="bi bi-trash"></i> Delete Selected
        </button>
    </div>
    <div class="card-body">
        @if($media->count() > 0)
        <div class="row g-4">
            @foreach($media as $item)
            <div class="col-md-3">
                <div class="card h-100 shadow-sm media-card">
                    <div class="position-relative">
                        @if($item->type == 'image')
                        <img src="{{ $item->file_url }}" class="card-img-top" alt="{{ $item->title }}" style="height: 200px; object-fit: cover;">
                        @elseif($item->type == 'video')
                        <div class="bg-dark d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="bi bi-play-circle text-white" style="font-size: 4rem;"></i>
                        </div>
                        @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="bi bi-file-earmark-text text-muted" style="font-size: 4rem;"></i>
                        </div>
                        @endif

                        <!-- Badges -->
                        <div class="position-absolute top-0 start-0 p-2">
                            <input type="checkbox" class="form-check-input media-checkbox" value="{{ $item->id }}">
                        </div>
                        <div class="position-absolute top-0 end-0 p-2">
                            @if($item->is_featured)
                            <span class="badge bg-warning"><i class="bi bi-star-fill"></i></span>
                            @endif
                            @if(!$item->is_active)
                            <span class="badge bg-danger">Inactive</span>
                            @endif
                        </div>
                    </div>

                    <div class="card-body">
                        <h6 class="card-title mb-2">{{ Str::limit($item->title, 30) }}</h6>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">
                                <span class="badge bg-secondary">{{ ucfirst($item->type) }}</span>
                            </small>
                            <small class="text-muted">{{ $item->formatted_file_size }}</small>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">
                                <i class="bi bi-folder"></i> {{ $item->category->name }}
                            </small>
                        </div>
                        <div class="d-flex justify-content-between text-muted small">
                            <span><i class="bi bi-eye"></i> {{ number_format($item->views) }}</span>
                            <span><i class="bi bi-download"></i> {{ number_format($item->downloads) }}</span>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-top-0">
                        <div class="btn-group btn-group-sm w-100" role="group">
                            <a href="{{ route('admin.media.edit', $item) }}" class="btn btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="{{ route('admin.media.show', $item) }}" class="btn btn-outline-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <form action="{{ route('admin.media.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $media->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
            <h4 class="mt-3">No media found</h4>
            <p class="text-muted">Upload your first media file to get started</p>
            <a href="{{ route('admin.media.create') }}" class="btn btn-primary">
                <i class="bi bi-cloud-upload"></i> Upload Media
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Bulk Delete Form -->
<form id="bulkDeleteForm" action="{{ route('admin.media.bulk-delete') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="ids" id="bulkDeleteIds">
</form>
@endsection

@push('styles')
<style>
    .media-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .media-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Handle checkbox selection
        $('.media-checkbox').on('change', function() {
            var checkedCount = $('.media-checkbox:checked').length;
            if (checkedCount > 0) {
                $('#bulkDeleteBtn').show();
            } else {
                $('#bulkDeleteBtn').hide();
            }
        });

        // Handle bulk delete
        $('#bulkDeleteBtn').on('click', function() {
            var ids = [];
            $('.media-checkbox:checked').each(function() {
                ids.push($(this).val());
            });

            if (ids.length > 0 && confirm('Are you sure you want to delete ' + ids.length + ' item(s)?')) {
                $('#bulkDeleteIds').val(JSON.stringify(ids));
                $('#bulkDeleteForm').submit();
            }
        });
    });
</script>
@endpush
