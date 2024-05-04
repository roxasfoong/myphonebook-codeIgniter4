<?php

namespace App\Helpers;

if (!function_exists('generate_random_string')) {
    function generate_random_string($length = 10, $type = 'alnum')
    {
        $pool = '';
        switch ($type) {
            case 'alnum':
                $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case 'alpha':
                $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case 'numeric':
                $pool = '0123456789';
                break;
            case 'hex':
                $pool = '0123456789abcdef';
                break;
            default:
                $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                break;
        }

        return substr(str_shuffle($pool), 0, $length);
    }
}

if (!function_exists('generate_random_email')) {
    function generate_random_email($length = 10, $domain = 'example.com')
    {
        $username = generate_random_string($length);
        return $username . '@' . $domain;
    }
}

?>