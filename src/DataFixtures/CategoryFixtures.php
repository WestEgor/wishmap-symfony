<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    /**
     * References of categories (for another fixtures)
     */
    public const CATEGORY_REFERENCES = array('first_c', 'second_c', 'third_c', 'four_c',
        'five_c', 'six_c', 'seven_c', 'eight_c', 'nine_c', 'ten_c');

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < count(self::CATEGORY_REFERENCES); $i++) {
            $category = new Category();
            $category->setName('name:' . $i);
            $manager->persist($category);
            $this->addReference(self::CATEGORY_REFERENCES[$i], $category);
        }
        $manager->flush();
    }
}