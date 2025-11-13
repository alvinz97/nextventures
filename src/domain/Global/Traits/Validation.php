<?php

namespace Domain\Global\Traits;

use Support\Helper\Helper;
use Illuminate\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

trait Validation
{
    use Helper;

    public function failedValidation(Validator|\Illuminate\Contracts\Validation\Validator $validator): void
    {
        if ($this->isAxiosRequest()) {
            throw new HttpResponseException(response()->json([
                'status'  => false,
                'message' => 'Validation errors',
                'errors'  => $validator->errors(),
            ], 422));
        }

        if ($this->isApiRequest()) {
            throw new HttpResponseException(response()->json([
                'status'  => false,
                'message' => 'Validation errors',
                'errors'  => $validator->errors(),
            ], 422));
        }

        throw new ValidationException(
            $validator,
            redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
        );
    }

    protected function isInertiaRequest(): bool
    {
        return request()->header('X-Inertia') === 'true';
    }

    protected function isAxiosRequest(): bool
    {
        return request()->expectsJson() && request()->isXmlHttpRequest();
    }

    protected function isApiRequest(): bool
    {
        return request()->expectsJson() && !request()->isXmlHttpRequest();
    }
}
