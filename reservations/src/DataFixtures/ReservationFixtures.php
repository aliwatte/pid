<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Reservation;
use App\DataFixtures\RepresentationFixtures;
use App\DataFixtures\UserFixtures;


class ReservationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $reservations = [
            [
                'representation'=>'ayiti-201810121330',
                'user'=>'ali',
                'places'=>2,
            ],
            [
                'representation'=>'cible-mouvante-201810142030',
                'user'=>'bob',
                'places'=>1,
            ],
            [
                'representation'=>'ayiti-201810122030',
                'user'=>'admin',
                'places'=>1,
            ],           
        ];

        foreach ($reservations as $record) {
			$reservation = new Reservation();
			
            //Assigner la référence de la représentation correspondante
			$reservation->setRepresentation($this->getReference($record['representation']));
			
			//Assigner la référence du user correspondant
			$reservation->setUser($this->getReference($record['user']));
			$reservation->setPlaces($record['places']);

            $manager->persist($reservation);            
        }

        $manager->flush();
    }

    public function getDependencies() {
        return [
            UserFixtures::class,
            RepresentationFixtures::class,
		];
    }
}
