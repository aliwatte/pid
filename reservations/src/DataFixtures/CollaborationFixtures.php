<?php

namespace App\DataFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Collaboration;
use App\DataFixtures\ArtistTypeFixtures;


class CollaborationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $collaborations = [
            [
                'artistType'=>'Bob-Sull-scènographe',
                'show'=>'ayiti',
            ],
            [
                'artistType'=>'Bob-Sull-comédien',
                'show'=>'ayiti',
            ],
            [
                'artistType'=>'Marc-Flynn-comédien',
                'show'=>'ayiti',
            ],
            [
                'artistType'=>'Fred-Durand-décorateur',
                'show'=>'ayiti',
			],
            [
                'artistType'=>'Marc-Flynn-scènographe',
                'show'=>'cible-mouvante',
			],
            [
                'artistType'=>'Marc-Flynn-comédien',
                'show'=>'cible-mouvante',
            ],
        ];

        foreach ($collaborations as $record) {
            $collaboration = new Collaboration();

            $collaboration->setArtistType($this->getReference($record['artistType']));
            $collaboration->setShows($this->getReference($record['show']));

            $manager->persist($collaboration);            
        }

        $manager->flush();
    }

    public function getDependencies() {
		return [
            ArtistTypeFixtures::class,
            ShowFixtures::class,
		];
    }
}

