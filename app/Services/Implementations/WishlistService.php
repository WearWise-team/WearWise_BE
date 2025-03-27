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

    public function getAllWishlists(int $userId)
    {
        return $this->wishlistRepository->getAll($userId);
    }

    public function createWishlist(int $userId,array $data)
    {
        return $this->wishlistRepository->create($userId,$data);
    }

    public function deleteWishlist(int $userId, int $productId)
    {
        return $this->wishlistRepository->delete($userId,$productId);
    }
}