<?php

namespace App\DataFixtures;
use App\Entity\Categories;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categorie = new Categories();
        $categorie->setLibelle("Permanent");

        $categorie2 = new Categories();
        $categorie2->setLibelle("Temporaire");

        $manager->persist($categorie);
        $manager->persist($categorie2);

        $manager->flush();
    }
}
