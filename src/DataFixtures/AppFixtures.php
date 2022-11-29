<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Country;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $source = $this->getCityCountrySource();

        foreach ($source as $row) {

            $country = new Country();
            $country->setCountryCode($row['country_code']);
            $country->setTitle($row['country_name']);
            $manager->persist($country);
            $manager->flush();

            foreach ($row['cities'] as $cityName) {

                $city = new City();
                $city->setTitle($cityName);
                $city->setCountry($country);
                $manager->persist($city);
            }

            $manager->flush();
        }

    }

    private function getCityCountrySource()
    {
        return [
            [ 'country_code' => 'AT', 'country_name' => 'Austria', 'cities' => ['Vienna']],
            [ 'country_code' => 'ES', 'country_name' => 'Spain','cities' => ['Madrid']],
            [ 'country_code' =>  'DE', 'country_name' => 'Germany','cities' => ['Dusseldorf', 'Berlin']],
            [ 'country_code' => 'NL', 'country_name' => 'Netherlands','cities' => ['Amsterdam']],
            [ 'country_code' => 'PL',  'country_name' => 'Poland','cities' => ['Warsaw']],
            [ 'country_code' =>  'UK' , 'country_name' => 'United Kingdom', 'cities' => ['London']],
        ];
    }
}
