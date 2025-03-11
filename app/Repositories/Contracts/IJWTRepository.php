<?php

namespace App\Repositories\Contracts;

interface IJWTRepository
{
    public function sendRequestToKling($humanImageBase64, $clothImageBase64, $token);
}