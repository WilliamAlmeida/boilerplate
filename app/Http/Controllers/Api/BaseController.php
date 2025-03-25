<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class BaseController extends Controller {
    protected $header_response = [
        'Content-Type' => 'application/json; charset=UTF-8',
        'Charset' => 'utf-8'
    ];

    protected $tenant;

    public function __construct()
    {
        $this->tenant = request()->user();
    }

    public function responseWithMessage($message, $code)
    {
        return [
            'status' => $code != 200 ? 'error' : 'success',
            'code' => $code,
            'message' => $message
        ];
    }
}