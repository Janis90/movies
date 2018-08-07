<?php

namespace AppBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;

use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Movie;

class LoadMovieData extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $movie1 = new Movie();
        $movie1->setTitle('Green Mile');
        $movie1->setYear(1992);
        $movie1->setTime(189);
        $movie1->setDescription('Description');

        $manager->persist($movie1);
        $manager->flush();
    }
}
