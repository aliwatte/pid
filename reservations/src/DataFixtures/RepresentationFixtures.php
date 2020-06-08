<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Representation;
use App\DataFixtures\ShowFixtures;
use App\DataFixtures\LocationFixtures;

class RepresentationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
         $reprensetations = [
            [
                'ref'=>'ayiti-201810121330',
                'show'=>'ayiti',
                'location'=>'belfius-art-collection',
                'schedule'=> new \DateTime('2020-10-12 13:30:00'),
            ],
            [
                'ref'=>'ayiti-201810122030',
                'show'=>'ayiti',
                'location'=>'theatre-royal-parc',
                'schedule'=>new \DateTime('2020-10-12 20:30:00'),
			],
            [
                'ref'=>'cible-mouvante-201810122030',
                'show'=>'cible-mouvante',
			'location'=>null,
                'schedule'=>new \DateTime('2020-10-08 13:30:00'),
			],
            [
                'ref'=>'cible-mouvante-201810142030',
                'show'=>'cible-mouvante',
				'location'=>null,
                'schedule'=>new \DateTime('2020-10-14 20:30:00'),
			],
            [
                'ref'=>'chanteur-belge-201810142030',
                'show'=>'ceci-n-est-pas-chanteur-belge',
				'location'=>null,
                'schedule'=>new \DateTime('2020-10-08 20:30:00'),
            ],             
        ];


        foreach ($reprensetations as $record) {
            $reprensetation = new Representation();
			
			//Assigner la référence du show correspondant
			$reprensetation->setShow($this->getReference($record['show']));
			
			//Assigner la référence du lieu correspondant (location)
			if($record['location'] != null) {
                $reprensetation->setLocation($this->getReference($record['location']));
            }
            $reprensetation->setSchedule($record['schedule']);

            $manager->persist($reprensetation); 
			
			$this->addReference($record['ref'], $reprensetation);  
		}

        $manager->flush();
    }

    public function getDependencies() {
        return [
            ShowFixtures::class,
            LocationFixtures::class,
        ];
    }
}
