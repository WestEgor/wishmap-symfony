<?php


namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    private UserPasswordEncoderInterface $passwordEncoder;

    public const USER_REFERENCES = array('first_u', 'second_u', 'third_u', 'four_u',
        'five_u', 'six_u', 'seven_u', 'eight_u', 'nine_u', 'ten_u');

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
        $file = '12131e23-7687-44ae-b71a-7e96e8da5e83-609d63d5a4201-609d720edf602.jpg';
        for ($i = 0; $i < count(self::USER_REFERENCES); $i++) {
            $user = new User();
            $user->setUsername('username' . $i);
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'password' . $i));
            $user->setNickname('nickname' . $i);
            $user->setRoles(['ROLE_USER']);
            $user->setAvatar($file);
            $user->setProfileDescription('Some description:' . $i);
            $manager->persist($user);
            $this->addReference(self::USER_REFERENCES[$i], $user);
        }
        $manager->flush();

    }
}