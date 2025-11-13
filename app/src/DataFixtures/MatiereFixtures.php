<?php

namespace App\DataFixtures;

use App\Entity\Agent;
use App\Entity\Matiere;
use App\DataFixtures\AgentFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MatiereFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $matieres = ['Mathématiques', 'Français', 'Histoire', 'Géographie', 'Physique-Chimie', 'SVT', 'Anglais', 'Espagnol', 'Allemand'];

        for ($i = 0; $i < 5; $i++) {
            $matiere = new Matiere();
            $matiere->setNom($matieres[$i]);
            $matiere->setAgent($this->getReference('agent_' . $i, Agent::class));
            $manager->persist($matiere);
            $this->addReference('matiere_' . $i, $matiere);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AgentFixtures::class,
        ];
    }
}
