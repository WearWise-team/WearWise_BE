<?php

namespace App\Services\Implementations;
use App\Repositories\Contracts\IWishlistRepository;
use App\Services\Contracts\IWishlistService;

class WishlistService implements IWishlistService
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

    public function deleteWishlist(int $id)
    {
        return $this->wishlistRepository->delete($id);
    }
}