<?php


namespace App\Service;


use App\Repository\WishMapRepository;
use Symfony\Component\Security\Core\Security;

class UserActionValidation
{

    private Security $security;

    /**
     * UserActionValidation constructor.
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function checkUserWishMapCard(WishMapRepository $wishMapRepository, int $id)
    {
        $user = $this->security->getUser();
        $userId = $user->getId();

        $wishMap = $wishMapRepository->find($id);
        $userWishMapId = $wishMap->getUser()->getId();

        return $userId === $userWishMapId;
    }

}