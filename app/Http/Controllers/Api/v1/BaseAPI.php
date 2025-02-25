<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Support\Facades\Log;

class BaseAPI {

    protected function getService()
    {
        return null;
    }

    function successResponse($data = null, $message = null, $code = 200)
    {
        // data is null, message is null, code is 200
        if ($data == null) {

            $code = 201;
            return response()->json([
                'status' => 'success',
                'message' => $message,
                'data' => null,
            ], $code);
        }

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    function errorResponse($message = null, $code = 400)
    {
        // Check if the provided code is a valid HTTP status code
        if (!is_int($code) || $code < 100 || $code >= 600) {
            // If not valid, default to 500 (Internal Server Error)
            $code = 500;
        }

        Log::error($message);
        return response()->json([
            'status' => 'error',
            'message' => $message,
        ], $code);
    }

}
