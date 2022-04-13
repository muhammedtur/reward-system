<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function handleResponse($data, $msg)
    {
        $res = [
            'status' => true,
            'data'    => $data,
            'message' => $msg,
        ];

        return response()->json($res, 200);
    }

    public function handleError($error, $code = 400)
    {
        $res = [
            'status' => false,
            'message' => $error,
        ];

        return response()->json($res, $code);
    }
}
