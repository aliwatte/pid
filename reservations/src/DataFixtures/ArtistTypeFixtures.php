<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\ArtistType;
use App\DataFixtures\ArtistFixtures;
use App\DataFixtures\TypeFixtures;


class ArtistTypeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $artistTypes = [
            [
                'artist'=>'Bob-Sull',
                'type'=>'scènographe',
            ],
            [
                'artist'=>'Bob-Sull',
                'type'=>'comédien',
            ],
            [
                'artist'=>'Marc-Flynn',
                'type'=>'comédien',
            ],
            [
                'artist'=>'Fred-Durand',
                'type'=>'décorateur',
            ],
            [
                'artist'=>'Marc-Flynn',
                'type'=>'scènographe',
            ],
        ];

        foreach ($artistTypes as $record) {
			$artistType = new ArtistType();
            //Assigner la référence de l'artiste correspondant
			$artistType->setArtist($this->getReference($record['artist']));
			//Assigner la référence du type correspondant
			$artistType->setType($this->getReference($record['type']));

            $manager->persist($artistType);

            $this->addReference($record['artist'].'-'.$record['type'], $artistType);
        }

        $manager->flush();
    }

    public function getDependencies() {
        return [
            ArtistFixtures::class,
            TypeFixtures::class,
        ];
    }
}
