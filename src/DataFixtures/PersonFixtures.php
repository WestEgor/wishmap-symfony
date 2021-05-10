<?php


namespace App\DataFixtures;


use App\Entity\Person;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PersonFixtures extends Fixture implements DependentFixtureInterface
{
    public const REFERENCES_PERSON = array('first_p', 'second_p', 'third_p', 'four_p', 'five_p',
        's_p', 'seven_p', 'eight_p', 'nine_p', 'ten_p');

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $user = $this->getReference(UserFixtures::REFERENCES[$i]);
            $person = new Person();
            $person->setName('name' . $i);
            $person->setSurname('surname' . $i);
            $person->setUser($user);
            $manager->persist($person);
            $this->addReference(self::REFERENCES_PERSON[$i], $person);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}