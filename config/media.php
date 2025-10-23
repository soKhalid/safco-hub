<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Maximum File Size
    |--------------------------------------------------------------------------
    |
    | The maximum file size for uploads in kilobytes (KB).
    | Default: 10240 KB (10 MB)
    |
    */

    'max_file_size' => env('MEDIA_MAX_FILE_SIZE', 10240),

    /*
    |--------------------------------------------------------------------------
    | Allowed Image Types
    |--------------------------------------------------------------------------
    |
    | Comma-separated list of allowed image file extensions.
    |
    */

    'allowed_image_types' => env('MEDIA_ALLOWED_IMAGE_TYPES', 'jpeg,jpg,png,gif,webp'),

    /*
    |--------------------------------------------------------------------------
    | Allowed Video Types
    |--------------------------------------------------------------------------
    |
    | Comma-separated list of allowed video file extensions.
    |
    */

    'allowed_video_types' => env('MEDIA_ALLOWED_VIDEO_TYPES', 'mp4,webm,mov'),

    /*
    |--------------------------------------------------------------------------
    | Allowed Document Types
    |--------------------------------------------------------------------------
    |
    | Comma-separated list of allowed document file extensions.
    |
    */

    'allowed_document_types' => env('MEDIA_ALLOWED_DOCUMENT_TYPES', 'pdf,doc,docx,ppt,pptx'),

    /*
    |--------------------------------------------------------------------------
    | Storage Disk
    |--------------------------------------------------------------------------
    |
    | The storage disk to use for media files.
    | Default: 'public'
    |
    */

    'storage_disk' => env('FILESYSTEM_DISK', 'public'),

    /*
    |--------------------------------------------------------------------------
    | Thumbnail Settings
    |--------------------------------------------------------------------------
    |
    | Settings for generating thumbnails.
    |
    */

    'thumbnail' => [
        'width' => 300,
        'height' => 300,
        'quality' => 80,
    ],

];
