<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Location;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class LocationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
	{
		$locations = [
			[
				'slug'=>'belfius-art-collection',
				'designation'=>'Belfius Art Collection',
				'address'=>'50 rue de l\'Écuyer',
				'locality'=>'Bruxelles',
				'website'=>null,
				'phone'=>null,
			],
			[
				'slug'=>'la-samaritaine',
				'designation'=>'La Samaritaine',
				'address'=>'rue de la samaritaine',
				'locality'=>'Bruxelles',
				'website'=>'www.lasamaritaine.be',
				'phone'=>'02/511.33.95',
			],
			[
				'slug'=>'theatre-royal-parc',
				'designation'=>'Théâtre Royal du Parc',
				'address'=>'Rue de la Loi 3',
				'locality'=>'Bruxelles',
				'website'=>'www.theatreduparc.be',
				'phone'=>null,
			]
        ];

        foreach ($locations as $record) {
            $location = new Location();
            $location->setSlug($record['slug']);
            $location->setDesignation($record['designation']);
            $location->setAddress($record['address']);
            $location->setLocality($this->getReference($record['locality']));
            $location->setWebsite($record['website']);
            $location->setPhone($record['phone']);
			$manager->persist($location);
			
			$this->addReference($record['slug'], $location);
        }

        $manager->flush();
    }

    public function getDependencies() {
        return [
            LocalityFixtures::class,
        ];
    }

}
