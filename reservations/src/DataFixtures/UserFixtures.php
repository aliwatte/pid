<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use App\DataFixtures\RoleFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $users = [
			[
                'login'=>'admin',
                'password'=>'123',
                'role'=>'admin',
                'firstname'=>'admin',
                'lastname'=>'admin',
                'email'=>'admin@ad.com',
                'langue'=>'fr',
            ],
            [
                'login'=>'bob',
                'password'=>'123',
                'role'=>'membre',
                'firstname'=>'bob',
                'lastname'=>'bob',
                'email'=>'bob@bob.com',
                'langue'=>'fr',
            ],
            [
                'login'=>'ali',
                'password'=>'123',
                'role'=>'membre',
                'firstname'=>'ali',
                'lastname'=>'ali',
				'email'=>'ali@ali.com',
                'langue'=>'en',
			],
			[
                'login'=>'fred',
                'password'=>'123',
                'role'=>'membre',
                'firstname'=>'fred',
                'lastname'=>'fred',
				'email'=>'fred@fred.com',
                'langue'=>'en',
			],
        ];

        foreach ($users as $record) {
            $user = new User();
			$user->setLogin($record['login']);

            //Hasher le mot de passe
			$user->setPassword(password_hash($record['password'], PASSWORD_BCRYPT));

			//Assigner la référence du rôle correspondant
			$user->setRole($this->getReference($record['role']));
            $user->setFirstname($record['firstname']);
            $user->setLastname($record['lastname']);
			$user->setEmail($record['email']);
            $user->setLangue($record['langue']);
            $manager->persist($user);
				
			//Ajout de la référence pour fixtures dépendantes (ReservationFixtures)
			//$this->addReference($record['login'], $user);
			
			//Reference to "admin" already exists, use method setReference in order to override it
			$this->setReference($record['login'], $user);
        }

        $manager->flush();
    }

    public function getDependencies() {
        return [
			RoleFixtures::class,
        ];
    }
}
