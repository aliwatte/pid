<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Show;
use App\DataFixtures\LocationFixtures;

class ShowFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $shows = [
            [
                'slug'=>'ayiti',
                'title'=>'Ayiti',
                'poster_url'=>'images/ayiti.jpg',
                'location'=>'belfius-art-collection',
                'bookable'=>1,
                'price'=>9.50,
            ],
            [
                'slug'=>'cible-mouvante',
				'title'=>'Cible Mouvante',
                'poster_url'=>' images/cible.jpg',
				'location'=>'la-samaritaine',
                'bookable'=>1,
                'price'=>8.00,
            ],
            [
                'slug'=>'ceci-n-est-pas-chanteur-belge',
                'title'=>'Ceci n\'est pas un chanteur belge',
                'poster_url'=>'/images/claudebelgesaison220.jpg',
				'location'=>'belfius-art-collection',
                'bookable'=>0,
                'price'=>7.50,
            ],
        ];

        foreach ($shows as $record) {
            $show = new Show();
            $show->setSlug($record['slug']);
            $show->setTitle($record['title']);
            $show->setPosterUrl($record['poster_url']);
            $show->setLocation($this->getReference($record['location']));
            $show->setBookable($record['bookable']);
            $show->setPrice($record['price']);
            $manager->persist($show);

            $this->addReference($record['slug'], $show);
        }

        $manager->flush();
    }

    public function getDependencies() {
        return [
            LocationFixtures::class,
        ];
    }
}
