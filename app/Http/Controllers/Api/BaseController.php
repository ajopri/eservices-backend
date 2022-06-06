<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends Controller
{
    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'code' => Response::HTTP_OK,
            'data' => $result,
            'message' => $message,
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    public function sendError($error, $errorMessage = [], $code = Response::HTTP_NOT_FOUND)
    {
        $response = [
            'success' => false,
            'code' => $code,
            'message' => $error
        ];

        if (!empty($errorMessage)) {
            $response['data'] = $errorMessage;
        }

        return response()->json($response, $code);
    }
}
