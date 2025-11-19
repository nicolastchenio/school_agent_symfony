<?php

namespace App\DataFixtures;

use App\Entity\Utilisateur;
use App\Enum\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Create an admin user
        $admin = new Utilisateur();
        $admin->setNom('Admin');
        $admin->setPrenom('User');
        $admin->setEmail('admin@example.com');
        $admin->setRoles([Role::ADMIN]);
        $admin->setPassword($this->userPasswordHasher->hashPassword($admin, 'password'));
        $manager->persist($admin);

        // Create a student user
        $student = new Utilisateur();
        $student->setNom('Student');
        $student->setPrenom('User');
        $student->setEmail('student@example.com');
        $student->setRoles([Role::ETUDIANT]);
        $student->setPassword($this->userPasswordHasher->hashPassword($student, 'password'));
        $manager->persist($student);

        $manager->flush();
    }
}
