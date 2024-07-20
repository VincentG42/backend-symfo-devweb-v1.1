<?php

namespace App\Service;

class PasswordGenerator
{
    public function generatePassword(int $length = 12): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+';
        return substr(str_shuffle($chars), 0, $length);
    }
}