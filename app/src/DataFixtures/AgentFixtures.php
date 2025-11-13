<?php

namespace App\DataFixtures;

use App\Entity\Agent;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AgentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 5; $i++) {
            $agent = new Agent();
            $agent->setNom($faker->name);
            $agent->setAvatar($faker->imageUrl(640, 480, 'people', true));
            $agent->setDescription($faker->text);
            $agent->setTemperature($faker->randomFloat(1, 0, 1));
            $agent->setSystemPrompt($faker->text);
            $manager->persist($agent);
            $this->addReference('agent_' . $i, $agent);
        }

        $manager->flush();
    }
}
