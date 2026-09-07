<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseController extends Controller
{

    public function withSuccess($data = [], $message = null)
    {
        $response = [
            'status' => true,
        ];
        if($message){
            $response['message'] = $message;
        }
        if($data){
            $response['data'] = $data;
        }
        return response()->json($response);
    }

    public function withError($message, $code)
    {
        $response = [
            'status' => false,
            'message' => $message,
        ];
        return response()->json($response, $code);
    }

    public function throw($message, $code)
    {
        throw new HttpResponseException(response()->json(["message" => $message], $code));
    }
}
