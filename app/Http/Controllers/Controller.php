<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendResponse($result, $message=null, $user = null)
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message,
            'code' => Response::HTTP_OK
        ];
        // $response['logged_user'] = $user?$user: auth('api')->user();
        return response()->json($response, Response::HTTP_OK, $headers = [], JSON_PRETTY_PRINT);
    }

    public function sendError($error, $code = 500, $errorMessages = [])
    {
        $response = [
            'success' => false,
            'message' => ['error' => [$error]],
            'code' => $code
        ];
        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }
    public function parseStr($data){
        $data=  str_replace("[","",$data);
        $data=  str_replace("]","",$data);
        $data=  str_replace("\"","",$data);
        $data=  str_replace("'","",$data);
        $data= explode(",",$data);
        return $data;
    }
}
