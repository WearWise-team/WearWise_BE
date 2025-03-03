<?php

namespace App\Services\Contracts;

interface IVirtualTryOnService
{
    public function tryOnClothes($personImage, $clothImage);
}