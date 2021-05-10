<?php


namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    private UserPasswordEncoderInterface $passwordEncoder;
    public const REFERENCES = array('first', 'second', 'third', 'four', 'five', 's', 'seven', 'eight', 'nine', 'ten');

    /**
     * RegistrationController constructor.
     * @param $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < count(self::REFERENCES); $i++) {
            $user = new User();
            $user->setUsername('username' . $i);
            $user->setEmail('email' . $i . '@email.com');
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'password' . $i));
            $manager->persist($user);
            $this->addReference(self::REFERENCES[$i], $user);
        }
        $manager->flush();

    }
}