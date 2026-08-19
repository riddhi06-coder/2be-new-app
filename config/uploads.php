<?php

/*
|--------------------------------------------------------------------------
| Upload size limits (project-wide)
|--------------------------------------------------------------------------
| Values are in KILOBYTES (Laravel's "max" validation unit).
| Change them here to adjust the limit everywhere at once.
|   images    -> 2 MB
|   documents -> 5 MB
|   videos    -> 5 MB
|
| NOTE: PHP's own upload_max_filesize / post_max_size in php.ini must be
| at least as large as these, otherwise the upload fails before validation.
*/

return [
    'image_max_kb'    => 2048, // 2 MB
    'document_max_kb' => 5120, // 5 MB
    'video_max_kb'    => 5120, // 5 MB
];
