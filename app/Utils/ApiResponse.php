<?php

namespace App\Utils;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    /**
     * Success response
     */
    public static function success($data = null, $message = 'Success', $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'code' => 'SUCCESS_' . $code,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Error response
     */
    public static function error($message = 'Error', $code = 'ERROR_500', $statusCode = 500, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'code' => $code,
            'message' => $message,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Validation error
     */
    public static function validationError($errors, $message = 'Validation failed.'): JsonResponse
    {
        return response()->json([
            'success' => false,
            'code' => 'VALIDATION_001',
            'message' => $message,
            'errors' => $errors,
        ], 422);
    }

    /**
     * Not found error
     */
    public static function notFound($message = 'Resource not found'): JsonResponse
    {
        return response()->json([
            'success' => false,
            'code' => 'NOT_FOUND_404',
            'message' => $message,
        ], 404);
    }

    /**
     * Unauthorized error
     */
    public static function unauthorized($message = 'Unauthorized'): JsonResponse
    {
        return response()->json([
            'success' => false,
            'code' => 'UNAUTHORIZED_401',
            'message' => $message,
        ], 401);
    }

    /**
     * Forbidden error
     */
    public static function forbidden($message = 'Forbidden'): JsonResponse
    {
        return response()->json([
            'success' => false,
            'code' => 'FORBIDDEN_403',
            'message' => $message,
        ], 403);
    }
}
