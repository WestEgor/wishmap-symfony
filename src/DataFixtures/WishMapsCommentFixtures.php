<?php


namespace App\DataFixtures;


use App\Entity\Category;
use App\Entity\Comments;
use App\Entity\Person;
use App\Entity\User;
use App\Entity\WishMap;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class WishMapsCommentFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {

        $user = new User();

        $user->setUsername('usernameXXX');
        $user->setEmail('emailXXX@email.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword('XXX');
        $manager->persist($user);

        $person = new Person();
        $person->setName('nameXXX');
        $person->setSurname('surnameXXX');
        $person->setUser($user);
        $manager->persist($person);

        $comment = new Comments();
        $comment->setComment('ExtracommXXX');
        $comment->setSendPerson($person);
        $manager->persist($comment);

        $category = new Category();
        $category->setPerson($person);
        $category->setName('CategoryXXX');
        $manager->persist($category);

        $wishMap = new WishMap();
        $wishMap->setPerson($person);
        $wishMap->setCategory($category);
        $wishMap->setComments([$comment]);
        $wishMap->setDescription('Some descrXXXX');
        $wishMap->setProcess(mt_rand(0, 100));
        $wishMap->setFinishDate(new \DateTime());
        $manager->persist($wishMap);

        var_dump($wishMap);

        $manager->flush();

    }
}