<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{

    /**
     * Return a JSON response for success.
     *
     * @param  array  $details
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($details)
    {

        //to fix the "message": "Malformed UTF-8 characters, possibly incorrectly encoded", 
        //added another two parms
        return response()->json($details["response"], $details["statusCode"], ['Content-type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Return a JSON response for error.
     *
     * @param  array  $message
     * @param  string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function error($message, $code)
    {
        return response()->json(['message' => $message], $code);
    }

}
