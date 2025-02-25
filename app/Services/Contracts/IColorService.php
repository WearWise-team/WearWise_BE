<?php

namespace App\Services\Contracts;

interface IColorService
{
    public function getAllColors();
    public function getColorById(int $id);
    public function createColor(array $data);
    public function updateColor(array $data, int $id);
    public function deleteColor(int $id);
}
