<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class MediaController extends Controller
{
    /**
     * Display a listing of the media.
     */
    public function index(Request $request)
    {
        $query = Media::with(['category', 'user']);

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $media = $query->latest()->paginate(20);
        $categories = Category::active()->ordered()->get();

        return view('admin.media.index', compact('media', 'categories'));
    }

    /**
     * Show the form for creating new media.
     */
    public function create()
    {
        $categories = Category::active()->ordered()->get();
        return view('admin.media.create', compact('categories'));
    }

    /**
     * Store a newly created media in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'files' => 'required|array|min:1',
            'files.*' => 'required|file|max:10240|mimes:jpeg,jpg,png,gif,webp,mp4,webm,mov,pdf,doc,docx,ppt,pptx',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ], [
            'files.required' => 'Please select at least one file to upload.',
            'files.*.max' => 'Each file must not exceed 10MB.',
            'files.*.mimes' => 'Invalid file type. Please upload images, videos, or documents only.',
        ]);

        $uploadedFiles = [];
        $errors = [];

        if (!$request->hasFile('files')) {
            return back()->with('error', 'No files were selected.')->withInput();
        }

        foreach ($request->file('files') as $index => $file) {
            try {
                $media = $this->processFileUpload($file, $validated);
                $uploadedFiles[] = $media;
            } catch (\Exception $e) {
                // Log error and collect error messages
                \Log::error('File upload error for file ' . ($index + 1) . ': ' . $e->getMessage());
                $errors[] = 'File ' . ($index + 1) . ': ' . $e->getMessage();
            }
        }

        if (count($uploadedFiles) === 0) {
            $errorMessage = 'No files were uploaded successfully.';
            if (!empty($errors)) {
                $errorMessage .= ' Errors: ' . implode(', ', $errors);
            }
            return back()->with('error', $errorMessage)->withInput();
        }

        $message = count($uploadedFiles) . ' file(s) uploaded successfully.';
        if (!empty($errors)) {
            $message .= ' However, ' . count($errors) . ' file(s) failed.';
        }

        return redirect()->route('admin.media.index')->with('success', $message);
    }

    /**
     * Process individual file upload.
     */
    private function processFileUpload($file, $data)
    {
        // Determine media type
        $mimeType = $file->getMimeType();
        $type = $this->determineMediaType($mimeType);

        // Generate unique filename
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();

        // Store file
        $path = $file->storeAs("media/{$type}s", $filename, 'public');

        // Create thumbnail for images and videos
        $thumbnailPath = null;
        if ($type === 'image') {
            $thumbnailPath = $this->createImageThumbnail($file, $filename);
        } elseif ($type === 'video') {
            $thumbnailPath = $this->createVideoThumbnail($path, $filename);
        }

        // Create media record
        return Media::create([
            'category_id' => $data['category_id'],
            'user_id' => Auth::id(),
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'type' => $type,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'mime_type' => $mimeType,
            'file_size' => round($file->getSize() / 1024), // Convert to KB
            'thumbnail_path' => $thumbnailPath,
            'is_featured' => isset($data['is_featured']) && $data['is_featured'] ? true : false,
            'is_active' => !isset($data['is_active']) || $data['is_active'] ? true : false,
        ]);
    }

    /**
     * Determine media type from mime type.
     */
    private function determineMediaType($mimeType)
    {
        if (Str::startsWith($mimeType, 'image/')) {
            return 'image';
        } elseif (Str::startsWith($mimeType, 'video/')) {
            return 'video';
        } else {
            return 'document';
        }
    }

    /**
     * Create thumbnail for image.
     */
    private function createImageThumbnail($file, $filename)
    {
        // For now, return null. In production, use Intervention Image or similar
        // $image = Image::make($file);
        // $image->fit(300, 300);
        // $thumbnailPath = "media/thumbnails/{$filename}";
        // Storage::disk('public')->put($thumbnailPath, $image->encode());
        // return $thumbnailPath;

        return null;
    }

    /**
     * Create thumbnail for video.
     */
    private function createVideoThumbnail($videoPath, $filename)
    {
        // For now, return null. In production, use FFmpeg
        return null;
    }

    /**
     * Display the specified media.
     */
    public function show(Media $media)
    {
        $media->load(['category', 'user']);
        return view('admin.media.show', compact('media'));
    }

    /**
     * Show the form for editing the specified media.
     */
    public function edit(Media $media)
    {
        $categories = Category::active()->ordered()->get();
        return view('admin.media.edit', compact('media', 'categories'));
    }

    /**
     * Update the specified media in storage.
     */
    public function update(Request $request, Media $media)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $media->update($validated);

        return redirect()->route('admin.media.index')->with('success', 'Media updated successfully.');
    }

    /**
     * Remove the specified media from storage.
     */
    public function destroy(Media $media)
    {
        $media->delete();
        return redirect()->route('admin.media.index')->with('success', 'Media deleted successfully.');
    }

    /**
     * Bulk delete media.
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:media,id',
        ]);

        Media::whereIn('id', $validated['ids'])->delete();

        return back()->with('success', count($validated['ids']) . ' media items deleted successfully.');
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(Media $media)
    {
        $media->update(['is_featured' => !$media->is_featured]);

        return back()->with('success', 'Featured status updated successfully.');
    }

    /**
     * Toggle active status.
     */
    public function toggleActive(Media $media)
    {
        $media->update(['is_active' => !$media->is_active]);

        return back()->with('success', 'Active status updated successfully.');
    }
}
