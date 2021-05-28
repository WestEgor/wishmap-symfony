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

    /**
     * Check actions on wish map cards.
     * We cannot update/delete foreign wish map card.
     * Method check id of current user id and wish map users_id
     * @param WishMapRepository $wishMapRepository
     * @param int $id id of User
     * @return bool
     * @return true if it User`s wish map card
     * @return false if it foreign User`s wish map card
     */
    public function checkUserWishMapCard(WishMapRepository $wishMapRepository, int $id)
    {
        $userId = $this->security->getUser()->getId(); // current user id
        $wishMap = $wishMapRepository->find($id);
        $userWishMapId = $wishMap->getUser()->getId(); // wish map user id
        return $userId === $userWishMapId;
    }

}