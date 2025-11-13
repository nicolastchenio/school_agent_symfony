<?php

namespace App\DataFixtures;

use App\Entity\NiveauScolaire;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class NiveauScolaireFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $niveaux = ['6ème', '5ème', '4ème', '3ème', 'Seconde', 'Première', 'Terminale'];

        foreach ($niveaux as $niveau) {
            $niveauScolaire = new NiveauScolaire();
            $niveauScolaire->setNiveau($niveau);
            $manager->persist($niveauScolaire);
            $this->addReference('niveau_' . $niveau, $niveauScolaire);
        }

        $manager->flush();
    }
}
