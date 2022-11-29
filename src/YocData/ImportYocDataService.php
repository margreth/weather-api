<?php

namespace App\YocData;


use App\Entity\City;
use App\Entity\WeatherData;
use App\Repository\CityRepository;
use App\Repository\CountryRepository;
use App\Repository\WeatherDataRepository;
use App\YocData\Model\YocData;
use Symfony\Component\PropertyAccess\PropertyAccessor;


class ImportYocDataService
{

    private $dataService;

    private $weatherRepository;

    private $cityRepository;

    private $countryRepository;

    /** @var City|null */
    private $city;


    public function __construct(YocDataService $dataService, WeatherDataRepository $weatherDataRepository, CityRepository $cityRepository, CountryRepository $countryRepository)
    {
        $this->dataService = $dataService;

        $this->weatherRepository = $weatherDataRepository;
        $this->cityRepository = $cityRepository;
        $this->countryRepository = $countryRepository;
    }

    public function run($params = [])
    {
        $this->populateData($this->dataService->getData($params));

        $this->dataService->clean();
    }


    private function populateData($data = [])
    {
        foreach ($data as $row) {
            $this->populateItem($row);
        }
    }

    private function populateItem($data = [])
    {

        $item = $this->createItem($data);

        if ($item && !$this->isItemExists($item)) {
            $this->weatherRepository->add($item, true);
        } else {
            $item = null;
        }
    }

    private function isItemExists(WeatherData $item)
    {
        return $this->weatherRepository->isItemExists($item);
    }

    private function createItem(YocData $model)
    {
        $accessor = new PropertyAccessor();
        $cityTitle = $accessor->getValue($model, 'cityTitle');
        $countryCode = $accessor->getValue($model, 'countryCode');
        $city = $this->getCity($cityTitle, $countryCode);

        if ($city) {
            $item = new WeatherData();
            $item->setCity($city);
            $this->fillItem($item, $model);
            return $item;
        }

    }


    private function getCity(string $cityTitle, string $countryCode): ?City
    {
        if ($this->city) {
            $isCurrent = $this->city->getCountry()->getCountryCode() == $countryCode && $this->city->getTitle() == $cityTitle;
            if (!$isCurrent) {
                $this->city = null;
            }
        }

        if (!$this->city) {
            $country = $this->countryRepository->findOneByCountryCode($countryCode);
            $this->city = $this->cityRepository->findOneBy([ 'country' => $country, 'title' => $cityTitle]);
        }

        return $this->city;
    }

    private function fillItem(WeatherData $item, YocData $model)
    {
        $accessor = new PropertyAccessor();

        foreach ($model->importData() as $key => $value) {
            $accessor->setValue($item, $key, $value);
        }

    }


}