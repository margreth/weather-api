<?php

namespace App\YocData;


use App\YocData\Client\YocDataClient;
use App\YocData\Model\YocData;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessor;


class YocDataService
{

    private $client;

    private $data = [];

    public function __construct(YocDataClient $client)
    {
        $this->client = $client;
    }

    public function getData(array $params)
    {
        $this->data = [];

        foreach ($params as $param) {
            if ($row = $this->client->getData($param))  {
                $this->addData($row);
            }
        }

        return $this->data;

    }

    public function clean()
    {
        $this->data = [];
        return $this;
    }

    private function addData(array $data = [])
    {
        array_walk($data, function($itemData) {

            if ($itemData && ($itemModel = $this->createItemModel($itemData))) {
                $this->data[] = $itemModel;
            }
        });
    }

    private function createItemModel(array $data = []): ?YocData
    {
        $model = new YocData();

        $data = $this->prepareItemData($data);

        $accessor = new PropertyAccessor();

        foreach ($data as $key => $value) {
            $accessor->setValue($model, $key, $value);
        }

        return $model;

    }

    private function prepareItemData(array $data)
    {

        $data = array_merge($data, $data['data'][0] ?? []);
        $list = [
            'date' => \DateTime::createFromFormat('Y-m-d',$data['datetime'] ?? null),
            'cityTitle'=> $data['city_name'] ?? null,
            'countryCode'=> $data['country_code'] ?? null,
            'temp'=> $data['temp'] ?? null,
            'minTemp'=> $data['min_temp'] ?? null,
            'maxTemp'=> $data['max_temp'] ?? null
        ];

        $resolver = $this->getResolver();

        return $resolver->resolve($list);
    }

    private function getResolver()
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired(['date', 'cityTitle', 'countryCode', 'temp', 'minTemp', 'maxTemp']);

        $resolver->setAllowedTypes('date', 'DateTime');
        $resolver->setAllowedTypes('temp', ['float', 'int']);
        $resolver->setAllowedTypes('minTemp', ['float', 'int']);
        $resolver->setAllowedTypes('maxTemp', ['float', 'int']);
        $resolver->setAllowedTypes('countryCode', 'string');
        $resolver->setAllowedTypes('cityTitle', 'string');

        return $resolver;
    }


}