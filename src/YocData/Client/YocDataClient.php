<?php

namespace App\YocData\Client;


use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\OptionsResolver\OptionsResolver;

class YocDataClient
{

    private $urlTmpl = 'http://yoc-media.github.io/weather/report/%s/%s.json';

    public function getData($params = [])
    {

        $resolver = new OptionsResolver();
        $resolver->setRequired(['city', 'country_code']);

        $params = $resolver->resolve($params);

        return $this->requestData($this->getUrl($params['country_code'], $params['city']));
    }


    private function getUrl($country, $city)
    {
        return sprintf($this->urlTmpl, $country, $city);
    }

    private function requestData($url)
    {
        $client = HttpClient::create();

        return $client->request('GET', $url)->toArray();

    }


}