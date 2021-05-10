<?php


namespace App\DataFixtures;


use App\Entity\Comments;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{

    public function getDependencies()
    {
        return [
            PersonFixtures::class
        ];
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 30; $i++) {
            $num = rand(0, 9);
            $person = $this->getReference(PersonFixtures::REFERENCES_PERSON[$num]);
            $comments = new Comments();
            $comments->setComment('comment' . $i);
            $comments->setSendPerson($person);
            $manager->persist($comments);
        }
        $manager->flush();
    }
}