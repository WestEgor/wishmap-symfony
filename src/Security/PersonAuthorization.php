<?php


namespace App\Security;


use App\Entity\Person;
use App\Repository\PersonRepository;
use Symfony\Component\Security\Core\Security;

class PersonAuthorization
{

    private Security $security;

    /**
     * PersonAuthorization constructor.
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function getLoggedPerson(PersonRepository $personRepository): ?Person
    {
        $user = $this->security->getUser();
        return $personRepository->findOneBy(
            ['user' => $user->getId()]
        );
    }


}