<?php

namespace Domain\Global\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserExists implements Rule
{
    protected bool $mustMatchAuth = false;

    public function __construct(bool $mustMatchAuth = false)
    {
        $this->mustMatchAuth = $mustMatchAuth;
    }

    public function passes($attribute, $value): bool
    {
        if (! User::query()->where('id', $value)->exists()) {
            return false;
        }

        if ($this->mustMatchAuth && Auth::check()) {
            return Auth::id() === (int) $value;
        }

        return true;
    }

    public function message(): string
    {
        return $this->mustMatchAuth
            ? 'The selected user is invalid or does not match the authenticated user.'
            : 'The selected user does not exist.';
    }
}
