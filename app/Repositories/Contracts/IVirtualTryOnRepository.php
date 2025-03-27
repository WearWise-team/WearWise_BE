<?php

namespace App\Repositories\Contracts;

interface IVirtualTryOnRepository
{
    public function sendRequest($personImage, $clothImage);
}