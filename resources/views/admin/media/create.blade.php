@extends('layouts.admin')

@section('title', 'Upload Media')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Upload Media</h1>
            <p class="page-subtitle">Upload images, videos, or documents</p>
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
                <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                    @csrf

                    <!-- Category Selection -->
                    <div class="mb-4">
                        <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="category_id" id="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Title -->
                    <div class="mb-4">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control" placeholder="Enter media title" required>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="3" placeholder="Enter media description (optional)"></textarea>
                    </div>

                    <!-- File Upload Area -->
                    <div class="mb-4">
                        <label class="form-label">Files <span class="text-danger">*</span></label>
                        <div class="upload-area" id="uploadArea">
                            <div class="upload-placeholder">
                                <i class="bi bi-cloud-arrow-up" style="font-size: 4rem; color: #4F46E5;"></i>
                                <h5 class="mt-3">Drag & Drop Files Here</h5>
                                <p class="text-muted mb-3">or click to browse</p>
                                <button type="button" class="btn btn-primary" id="browseBtn">
                                    <i class="bi bi-folder-open"></i> Browse Files
                                </button>
                                <input type="file" name="files[]" id="fileInput" class="d-none" multiple accept="image/*,video/*,.pdf,.doc,.docx,.ppt,.pptx">
                            </div>
                            <div id="filePreview" class="file-preview" style="display: none;"></div>
                        </div>
                        <div class="form-text">
                            <strong>Supported formats:</strong>
                            <br>Images: JPEG, PNG, GIF, WebP
                            <br>Videos: MP4, WebM, MOV
                            <br>Documents: PDF, DOC, DOCX, PPT, PPTX
                            <br><strong>Max file size:</strong> 10MB per file
                        </div>
                    </div>

                    <!-- Options -->
                    <div class="mb-4">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1">
                            <label class="form-check-label" for="is_featured">
                                Mark as Featured
                            </label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">
                                Active
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                            <i class="bi bi-cloud-upload"></i> Upload Media
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .upload-area {
        border: 3px dashed #D1D5DB;
        border-radius: 1rem;
        padding: 3rem;
        text-align: center;
        transition: all 0.3s;
        background: #F9FAFB;
    }

    .upload-area.dragover {
        border-color: #4F46E5;
        background: #EEF2FF;
    }

    .file-preview {
        padding: 1rem 0;
    }

    .file-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: white;
        border-radius: 0.5rem;
        margin-bottom: 0.5rem;
        border: 1px solid #E5E7EB;
    }

    .file-item img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 0.5rem;
        margin-right: 1rem;
    }

    .file-item .file-icon {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #F3F4F6;
        border-radius: 0.5rem;
        margin-right: 1rem;
        font-size: 2rem;
    }

    .file-item .file-info {
        flex-grow: 1;
    }

    .file-item .file-name {
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    .file-item .file-size {
        font-size: 0.875rem;
        color: #6B7280;
    }

    .file-item .remove-file {
        color: #EF4444;
        cursor: pointer;
        font-size: 1.5rem;
    }
</style>
@endpush

@push('scripts')
<script>
    // Use vanilla JavaScript to avoid jQuery conflicts
    document.addEventListener('DOMContentLoaded', function() {
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('fileInput');
        const filePreview = document.getElementById('filePreview');
        const uploadPlaceholder = document.querySelector('.upload-placeholder');
        const browseBtn = document.getElementById('browseBtn');
        const uploadForm = document.getElementById('uploadForm');
        const submitBtn = document.getElementById('submitBtn');

        // Browse button click
        browseBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            fileInput.click();
        });

        // Upload area click
        uploadArea.addEventListener('click', function(e) {
            // Only trigger if clicking on the upload area itself, not on buttons or file items
            if (e.target === uploadArea || e.target.closest('.upload-placeholder')) {
                if (!e.target.classList.contains('btn') && !e.target.closest('.file-item')) {
                    fileInput.click();
                }
            }
        });

        // File input change
        fileInput.addEventListener('change', function() {
            if (this.files && this.files.length > 0) {
                displayFiles(this.files);
                uploadPlaceholder.style.display = 'none';
                filePreview.style.display = 'block';
            }
        });

        // Drag and drop events
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            uploadArea.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            uploadArea.classList.remove('dragover');
        });

        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            uploadArea.classList.remove('dragover');

            const files = e.dataTransfer.files;
            if (files && files.length > 0) {
                // Set files to input element
                fileInput.files = files;
                displayFiles(files);
                uploadPlaceholder.style.display = 'none';
                filePreview.style.display = 'block';
            }
        });

        function displayFiles(files) {
            filePreview.innerHTML = '';

            Array.from(files).forEach((file, index) => {
                const fileItem = document.createElement('div');
                fileItem.className = 'file-item';

                // File preview or icon
                if (file.type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    fileItem.appendChild(img);
                } else {
                    let icon = 'file-earmark';
                    if (file.type.startsWith('video/')) {
                        icon = 'play-circle';
                    } else if (file.type.includes('pdf')) {
                        icon = 'file-pdf';
                    } else if (file.type.includes('word') || file.type.includes('document')) {
                        icon = 'file-word';
                    } else if (file.type.includes('powerpoint') || file.type.includes('presentation')) {
                        icon = 'file-ppt';
                    }

                    const fileIcon = document.createElement('div');
                    fileIcon.className = 'file-icon';
                    fileIcon.innerHTML = '<i class="bi bi-' + icon + '"></i>';
                    fileItem.appendChild(fileIcon);
                }

                // File info
                const fileInfo = document.createElement('div');
                fileInfo.className = 'file-info';

                const fileName = document.createElement('div');
                fileName.className = 'file-name';
                fileName.textContent = file.name;

                const fileSize = document.createElement('div');
                fileSize.className = 'file-size';
                fileSize.textContent = formatFileSize(file.size);

                fileInfo.appendChild(fileName);
                fileInfo.appendChild(fileSize);
                fileItem.appendChild(fileInfo);

                // Remove button
                const removeBtn = document.createElement('i');
                removeBtn.className = 'bi bi-x-circle remove-file';
                removeBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    // Clear file input and preview
                    fileInput.value = '';
                    filePreview.style.display = 'none';
                    uploadPlaceholder.style.display = 'block';
                });
                fileItem.appendChild(removeBtn);

                filePreview.appendChild(fileItem);
            });
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }

        // Form validation on submit
        uploadForm.addEventListener('submit', function(e) {
            const files = fileInput.files;

            if (!files || files.length === 0) {
                e.preventDefault();
                alert('Please select at least one file to upload');
                return false;
            }

            // Show loading state
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Uploading...';
            submitBtn.disabled = true;

            // Let the form submit normally
            return true;
        });
    });
</script>
@endpush
