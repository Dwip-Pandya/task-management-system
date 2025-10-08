<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as BaseHandler;
use Throwable;
use Illuminate\Http\Request;

class Handler extends BaseHandler
{
    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        // If request is AJAX, return JSON
        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'Something went wrong. Please try again later.'
            ], 500);
        }

        // For web requests, show custom error view
        return response()->view('errors.custom', [
            'error_message' => $exception->getMessage()
        ], 500);
    }
}
