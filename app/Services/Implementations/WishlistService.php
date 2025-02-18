<?php

namespace App\Services\Implementations;
use App\Repositories\Contracts\IWishlistRepository;

class WishlistService
{
    protected $wishlistRepository;

    public function __construct(IWishlistRepository $wishlistRepository)
    {
        $this->wishlistRepository = $wishlistRepository;
    }

    public function getAllWishlists()
    {
        return $this->wishlistRepository->getAll();
    }

    public function getWishlistById(int $id)
    {
        return $this->wishlistRepository->findById($id);
    }

    public function createWishlist(array $data)
    {
        return $this->wishlistRepository->create($data);
    }

    public function updateWishlist(int $id, array $data)
    {
        return $this->wishlistRepository->update($id, $data);
    }

    public function deleteWishlist(int $id)
    {
        return $this->wishlistRepository->delete($id);
    }
}