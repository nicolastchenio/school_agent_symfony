<?php

namespace App\DataFixtures;

use App\Entity\Agent;
use App\Entity\NiveauScolaire;
use App\Entity\Utilisateur;
use App\Enum\Role;
use App\DataFixtures\NiveauScolaireFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UtilisateurFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $niveaux = ['6ème', '5ème', '4ème', '3ème', 'Seconde', 'Première', 'Terminale'];

        for ($i = 0; $i < 20; $i++) {
            $utilisateur = new Utilisateur();
            $utilisateur->setEmail($faker->email);
            $utilisateur->setRoles([Role::ETUDIANT]);
            $utilisateur->setPassword($this->passwordHasher->hashPassword($utilisateur, 'password'));
            $utilisateur->setNom($faker->lastName);
            $utilisateur->setPrenom($faker->firstName);
            $utilisateur->setNiveauScolaire($this->getReference('niveau_' . $niveaux[array_rand($niveaux)], NiveauScolaire::class));
            
            for ($j = 0; $j < $faker->numberBetween(1, 3); $j++) {
                $utilisateur->addAgent($this->getReference('agent_' . $faker->numberBetween(0, 4), Agent::class));
            }

            $manager->persist($utilisateur);
            $this->addReference('utilisateur_' . $i, $utilisateur);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            NiveauScolaireFixtures::class,
            AgentFixtures::class,
        ];
    }
}
