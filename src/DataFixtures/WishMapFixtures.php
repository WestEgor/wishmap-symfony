<?php

namespace App\DataFixtures;

use App\Entity\WishMap;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class WishMapFixtures extends Fixture implements DependentFixtureInterface
{

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            CategoryFixtures::class,
            CommentFixtures::class
        ];
    }


    public function load(ObjectManager $manager)
    {

        $userSize = count(UserFixtures::USER_REFERENCES) - 1;
        $categorySize = count(CategoryFixtures::CATEGORY_REFERENCES) - 1;
        $image = 'Square-200x200-6096ab3616005-60a24e83c4ec2.png';
        for ($i = 0; $i < 50; $i++) {
            $userId = mt_rand(0, $userSize);
            $categoryId = mt_rand(0, $categorySize);
            $wishMap = new WishMap();

            if ($i < 19) {
                $comment = $this->getReference(CommentFixtures::COMMENTS_REFERENCES[$i]);
                $wishMap->setComments([$comment]);
            }


            $user = $this->getReference(UserFixtures::USER_REFERENCES[$userId]);
            $category = $this->getReference(CategoryFixtures::CATEGORY_REFERENCES[$categoryId]);


            $wishMap->setUser($user);
            $wishMap->setName('name:' . $i);
            $wishMap->setImage($image);
            $wishMap->setCategory($category);
            $wishMap->setDescription('Some descr' . $i);
            $wishMap->setProgress(mt_rand(0, 100));
            $wishMap->setFinishDate(new \DateTime());
            $manager->persist($wishMap);
        }
        $manager->flush();
    }
}