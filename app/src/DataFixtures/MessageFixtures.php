<?php

namespace App\DataFixtures;

use App\Entity\Conversation;
use App\Entity\Message;
use App\DataFixtures\ConversationFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class MessageFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 200; $i++) {
            $message = new Message();
            $message->setQuestion($faker->sentence());
            $message->setReponse($faker->sentence());
            $message->setConversation($this->getReference('conversation_' . $faker->numberBetween(0, 49), Conversation::class));
            $manager->persist($message);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ConversationFixtures::class,
        ];
    }
}
