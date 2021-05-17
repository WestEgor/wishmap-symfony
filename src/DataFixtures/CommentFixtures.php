<?php


namespace App\DataFixtures;


use App\Entity\Comments;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{

    public const COMMENTS_REFERENCES = array('first_com', 'second_com', 'third_com', 'four_com',
        'five_com', 'six_com', 'seven_com', 'eight_com', 'nine_com', 'ten_com', 'eleven', 'twelve', 'thirteen',
        'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen', 'twenty');

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 20; $i++) {
            $num = rand(0, 9);
            $user = $this->getReference(UserFixtures::USER_REFERENCES[$num]);
            $comments = new Comments();
            $comments->setComment('comment' . $i);
            $comments->setSendUser($user);
            $manager->persist($comments);
            $this->addReference(self::COMMENTS_REFERENCES[$i], $comments);

        }
        $manager->flush();
    }
}