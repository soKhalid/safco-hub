<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Get statistics
        $stats = [
            'total_media' => Media::count(),
            'total_images' => Media::where('type', 'image')->count(),
            'total_videos' => Media::where('type', 'video')->count(),
            'total_documents' => Media::where('type', 'document')->count(),
            'total_categories' => Category::count(),
            'total_users' => User::count(),
            'total_views' => Media::sum('views'),
            'total_downloads' => Media::sum('downloads'),
            'storage_used' => $this->formatBytes(Media::sum('file_size') * 1024), // Convert KB to bytes
        ];

        // Get recent media
        $recentMedia = Media::with(['category', 'user'])
            ->latest()
            ->take(10)
            ->get();

        // Get popular media
        $popularMedia = Media::with(['category', 'user'])
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();

        // Get media by category
        $mediaByCategory = Category::withCount('media')
            ->having('media_count', '>', 0)
            ->orderBy('media_count', 'desc')
            ->get();

        // Get media by type for chart
        $mediaByType = Media::select('type', DB::raw('count(*) as count'))
            ->groupBy('type')
            ->get()
            ->pluck('count', 'type');

        // Get recent uploads by month
        $uploadsByMonth = Media::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('count(*) as count')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentMedia',
            'popularMedia',
            'mediaByCategory',
            'mediaByType',
            'uploadsByMonth'
        ));
    }

    /**
     * Format bytes to human readable format.
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
