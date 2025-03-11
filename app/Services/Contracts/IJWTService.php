<?php

namespace App\Services\Contracts;

interface IJWTService
{
    public function generateToken();
    public function tryOnClothesWithKling(string $humanImageBase64, string $clothImageBase64);
}
