<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'user_id',
        'title',
        'description',
        'type',
        'file_name',
        'file_path',
        'mime_type',
        'file_size',
        'thumbnail_path',
        'views',
        'downloads',
        'is_featured',
        'is_active',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'views' => 'integer',
        'downloads' => 'integer',
        'file_size' => 'integer',
        'metadata' => 'array',
    ];

    /**
     * Get the category that owns the media.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the user that uploaded the media.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the full URL of the media file.
     */
    public function getFileUrlAttribute()
    {
        return Storage::url($this->file_path);
    }

    /**
     * Get the full URL of the thumbnail.
     */
    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail_path) {
            return Storage::url($this->thumbnail_path);
        }

        // Return default thumbnail based on type
        return asset("images/default-{$this->type}.png");
    }

    /**
     * Get formatted file size.
     */
    public function getFormattedFileSizeAttribute()
    {
        $size = $this->file_size;

        if ($size >= 1048576) {
            return round($size / 1048576, 2) . ' GB';
        } elseif ($size >= 1024) {
            return round($size / 1024, 2) . ' MB';
        } else {
            return $size . ' KB';
        }
    }

    /**
     * Increment views counter.
     */
    public function incrementViews()
    {
        $this->increment('views');
    }

    /**
     * Increment downloads counter.
     */
    public function incrementDownloads()
    {
        $this->increment('downloads');
    }

    /**
     * Scope a query to only include active media.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include featured media.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to filter by type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to search media.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('file_name', 'like', "%{$search}%");
        });
    }

    /**
     * Delete the media file when model is deleted.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($media) {
            // Delete the file from storage
            if (Storage::exists($media->file_path)) {
                Storage::delete($media->file_path);
            }

            // Delete thumbnail if exists
            if ($media->thumbnail_path && Storage::exists($media->thumbnail_path)) {
                Storage::delete($media->thumbnail_path);
            }
        });
    }
}
