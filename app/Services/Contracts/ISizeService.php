<?php

namespace App\Services\Contracts;

interface ISizeService
{
    public function getAllSizes();
    public function getSizeById(int $id);
    public function createSize(array $data);
    public function updateSize(array $data, int $id);
    public function deleteSize(int $id);
}