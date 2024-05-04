<?php

namespace App\Validation;

use CodeIgniter\Validation\Validation;

class CustomValidation extends \Config\Validation
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Custom rule to validate the image size.
     *
     * @param string $str
     * @param string $field
     * @param array  $data
     *
     * @return bool
     */

    public function validateImageSize($image_location)
    {
        if (empty($_FILES[$image_location]['name'])) {
            return true;
        }

        $uploaded_file_info = $_FILES[$image_location];
        $file_size = $uploaded_file_info['size'];

        $max_size_bytes = 10 * 1024 * 1024; // 10 MB

        if (! preg_match('/^image\//', $_FILES[$image_location]['type'])) {
            return false; // Not an image
        }

        if ($file_size > $max_size_bytes) {
            return false; // Exceeds maximum size
        }

        return true; // Validation passed
    }

}