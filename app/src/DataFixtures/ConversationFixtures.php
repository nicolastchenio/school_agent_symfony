<?php

namespace App\DataFixtures;

use App\Entity\Agent;
use App\Entity\Conversation;
use App\Entity\Utilisateur;
use App\DataFixtures\UtilisateurFixtures;
use App\DataFixtures\AgentFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ConversationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 50; $i++) {
            $conversation = new Conversation();
            $conversation->setTitre($faker->sentence(3));
            $conversation->setCreerLe($faker->dateTimeThisYear());
            $conversation->setUtilisateur($this->getReference('utilisateur_' . $faker->numberBetween(0, 19), Utilisateur::class));
            $conversation->setAgent($this->getReference('agent_' . $faker->numberBetween(0, 4), Agent::class));
            $manager->persist($conversation);
            $this->addReference('conversation_' . $i, $conversation);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UtilisateurFixtures::class,
            AgentFixtures::class,
        ];
    }
}
