<?php

namespace Islamikit\Starterkit\Http\Responses;

use Laravel\Fortify\Http\Responses\LoginResponse as FortifyLoginResponse;
use Illuminate\Http\Request;

class LoginResponse extends FortifyLoginResponse
{
    public function toResponse($request)
    {
        return parent::toResponse($request);
    }
}