<?php

namespace App\DataFixtures;

use App\Entity\Userlog;
use App\Entity\Utilisateur;
use App\DataFixtures\UtilisateurFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserlogFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 20; $i++) {
            $userlog = new Userlog();
            $userlog->setDernierConnection($faker->dateTimeThisYear());
            $userlog->setUtilisateur($this->getReference('utilisateur_' . $i, Utilisateur::class));
            $manager->persist($userlog);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UtilisateurFixtures::class,
        ];
    }
}
