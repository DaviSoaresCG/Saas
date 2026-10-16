<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    |
    | Intervention Image supports "GD Library" and "Imagick" to process images
    | internally. Depending on your PHP setup, you can choose one of them.
    |
    | Supported drivers:
    | - Intervention\Image\Drivers\Gd\Driver::class
    | - Intervention\Image\Drivers\Imagick\Driver::class
    |
    */

    'driver' => (env('IMAGE_DRIVER', 'gd') === 'imagick' || env('IMAGE_DRIVER') === Intervention\Image\Drivers\Imagick\Driver::class)
        ? Intervention\Image\Drivers\Imagick\Driver::class
        : Intervention\Image\Drivers\Gd\Driver::class,

];
