<?php

use App\Models\User;
use Illuminate\Support\Carbon;

if (!function_exists('findUserById')) {
    function findUserById(int $id)
    {
        return User::where('id', $id)->first();
    }
}

if (!function_exists('formattedDateTime')) {
    function formattedDateTime($date, ?string $format = 'Y-m-d H:i:s A'): ?string
    {
        return $date ? Carbon::parse($date)->format($format) : null;
    }
}
