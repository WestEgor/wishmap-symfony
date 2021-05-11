<?php


namespace App\DataFixtures;


use App\Entity\Category;
use App\Entity\Person;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture implements DependentFixtureInterface
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

            $category = new Category();
            $category->setName('category' . $i);

            $category->setPerson($person);
            $manager->persist($category);
        }
        $manager->flush();
    }
}