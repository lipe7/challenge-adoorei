<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Throwable;

class AdooreiException extends Exception
{
    public static function handle(Throwable $ex)
    {
        if ($ex instanceof ModelNotFoundException) {
            return response()->json([
                'success' => false,
                'message' => 'sale not found'
            ], Response::HTTP_NOT_FOUND);
        } elseif ($ex instanceof QueryException) {
            return response()->json([
                'success' => false,
                'message' => 'a database error occurred: ' . $ex->getCode()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } elseif ($ex instanceof ValidationException) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . $ex->getMessage(),
                'errors' => $ex->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'an unexpected error occurred: ' . $ex->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
