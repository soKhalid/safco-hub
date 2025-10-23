<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaGalleryController extends Controller
{
    /**
     * Display the media gallery.
     */
    public function index(Request $request)
    {
        $query = Media::with(['category', 'user'])->active();

        // Filter by type
        if ($request->filled('type')) {
            $query->ofType($request->type);
        }

        // Filter by category
        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'popular':
                $query->orderBy('views', 'desc');
                break;
            case 'downloads':
                $query->orderBy('downloads', 'desc');
                break;
            case 'oldest':
                $query->oldest();
                break;
            default:
                $query->latest();
        }

        $media = $query->paginate(12);
        $categories = Category::active()->ordered()->withCount('media')->get();
        $featuredMedia = Media::active()->featured()->take(6)->get();

        return view('gallery.index', compact('media', 'categories', 'featuredMedia'));
    }

    /**
     * Display the specified media.
     */
    public function show($id)
    {
        $media = Media::with(['category', 'user'])->active()->findOrFail($id);

        // Increment views
        $media->incrementViews();

        // Get related media
        $relatedMedia = Media::active()
            ->where('category_id', $media->category_id)
            ->where('id', '!=', $media->id)
            ->take(6)
            ->get();

        return view('gallery.show', compact('media', 'relatedMedia'));
    }

    /**
     * Download media file.
     */
    public function download(Media $media)
    {
        // Increment downloads counter
        $media->incrementDownloads();

        // Check if file exists
        if (!Storage::disk('public')->exists($media->file_path)) {
            abort(404, 'File not found.');
        }

        return Storage::disk('public')->download($media->file_path, $media->file_name);
    }

    /**
     * Get media by category.
     */
    public function byCategory($slug)
    {
        $category = Category::where('slug', $slug)->active()->firstOrFail();
        $media = Media::active()
            ->where('category_id', $category->id)
            ->latest()
            ->paginate(12);

        $categories = Category::active()->ordered()->withCount('media')->get();

        return view('gallery.category', compact('category', 'media', 'categories'));
    }

    /**
     * Search media.
     */
    public function search(Request $request)
    {
        $search = $request->get('q', '');
        $media = Media::active()
            ->search($search)
            ->with(['category', 'user'])
            ->latest()
            ->paginate(12);

        $categories = Category::active()->ordered()->withCount('media')->get();

        return view('gallery.search', compact('media', 'search', 'categories'));
    }

    /**
     * Get media by type.
     */
    public function byType($type)
    {
        if (!in_array($type, ['image', 'video', 'document'])) {
            abort(404);
        }

        $media = Media::active()
            ->ofType($type)
            ->latest()
            ->paginate(12);

        $categories = Category::active()->ordered()->withCount('media')->get();

        return view('gallery.type', compact('type', 'media', 'categories'));
    }
}
