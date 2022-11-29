<?php

namespace App\Command;


use App\Repository\CityRepository;
use App\Repository\CountryRepository;
use App\YocData\ImportYocDataService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DataImportCommand extends Command
{
    protected static $defaultName = 'app:data:import';
    protected static $defaultDescription = 'Data import command';


    private $service;
    private $countryRepository;
    private $cityRepository;

    public function __construct(ImportYocDataService $service, CountryRepository $countryRepository, CityRepository $cityRepository)
    {
        $this->service = $service;
        $this->countryRepository = $countryRepository;
        $this->cityRepository = $cityRepository;

        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addOption('country', null, InputOption::VALUE_OPTIONAL, 'Country_code option')
            ->addOption('city', null, InputOption::VALUE_OPTIONAL, 'City option');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $param = $this->prepareParams($input);

        $this->service->run($param);

        return 0;

    }

    private function prepareParams(InputInterface $input)
    {
        $country = $input->getOption('country');

        $city = $input->getOption('city');

        $list = $params = [];

        if ($city) {

            $cityItem = $this->cityRepository->findOneBy(['title' => $city]);
            if (!$cityItem) {
                throw new \Exception(sprintf('Invalid city option "%s"', $city));
            }

            $list = [$cityItem];

        } elseif ($country) {

            $countryItem = $this->countryRepository->findOneBy(['countryCode' => $country]);
            if (!$countryItem) {
                throw new \Exception(sprintf('Invalid country option "%s"', $city));
            }

            $list = $this->cityRepository->findBy(['country' => $countryItem]);

        } else {

            $list = $this->cityRepository->findBy([]);
        }

        if ($list) {
            foreach ($list as $item) {
                $params[] = ['country_code' => $item->getCountry()->getCountryCode(), 'city' => $item->getTitle()];
            }
        }


        return $params;

    }
}
