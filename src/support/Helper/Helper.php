<?php

namespace Support\Helper;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Request;
use Throwable;

trait Helper
{
    public function getCurrentRoute(): object|string|null
    {
        return Request::route();
    }

    public function throwable(Exception|Throwable $exception, ?bool $isAPI = false): JsonResponse|RedirectResponse
    {
        $errorMessage = [
            'title' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'line' => $exception->getLine(),
            'file' => $exception->getFile(),
            'previous' => $exception->getPrevious() ? $exception->getPrevious()->getMessage() : null,
            'getTrace' => $exception->getTrace(),
        ];

        if ($isAPI) {
            return response()->json([
                'status' => false,
                'message' => $errorMessage,
            ], 500);
        }

        if ($this->requestTypeCheck()) {
            return response()->json([
                'status' => false,
                'message' => $errorMessage,
            ], 500);
        }

        return redirect()->back()->withFlash([
            'status' => false,
            'type' => 'error',
            'title' => 'Exception Occur',
            'message' => $errorMessage,
        ]);
    }

    public function requestTypeCheck(
        string $type = 'api'
    ): bool
    {
        return in_array($type, request()->route()->getAction('middleware'), true);
    }
}
