<?php

namespace App\YocData\Model;


class YocData
{

    protected $date;

    protected $cityTitle;

    protected $countryCode;

    protected $temp;

    protected $maxTemp;

    protected $minTemp;


    public function __set($name, $value)
    {

        if (property_exists($this, $name)) {
            $this->$name = $value;
        }

        return $this;
    }

    public function __get($name)
    {

        if (property_exists($this, $name)) {
            return $this->$name;
        }

    }

    public function importData()
    {
        return [
            'date' => $this->date,
            'temp' => $this->temp,
            'minTemp' => $this->minTemp,
            'maxTemp' => $this->maxTemp
        ];
    }

}