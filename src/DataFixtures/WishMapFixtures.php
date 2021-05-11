<?php


namespace App\DataFixtures;


use App\Entity\Category;
use App\Entity\Comments;
use App\Entity\Person;
use App\Entity\WishMap;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class WishMapFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $personRep = $manager->getRepository(Person::class);
        $categoryRep = $manager->getRepository(Category::class);
        $commentsRep = $manager->getRepository(Comments::class);

        for ($i = 0; $i < 50; $i++) {
            $person = $personRep->findOneBy(['name' => 'name' . mt_rand(0, 9)]);
            $category = $categoryRep->findOneBy(['person' => $person]);
            $comments = $commentsRep->findOneBy(['comment' => 'comment' . mt_rand(0, 29)]);
            $wishMap = new WishMap();
            $wishMap->setPerson($person);
            $wishMap->setCategory($category);
            $wishMap->setComments($comments);
            $wishMap->setDescription('Some descr' . $i);
            $wishMap->setProcess(mt_rand(0, 100));
            $wishMap->setFinishDate(new \DateTime());
            $manager->persist($wishMap);
        }

        $manager->flush();


    }


}