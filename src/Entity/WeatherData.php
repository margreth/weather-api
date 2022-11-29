<?php

namespace App\Entity;


use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\{
    DateFilter,
    OrderFilter,
    RangeFilter,
    SearchFilter
};
use App\Repository\WeatherDataRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;


/**
 * @ORM\Entity(repositoryClass=WeatherDataRepository::class)
 * @ApiResource(
*      normalizationContext= {"groups"= {"weather:read"}},
 *     itemOperations={"get"},
 *     collectionOperations={
 *      "get"={
 *              "method"="GET",
 *              "openapi_context" = {
 *                  "parameters" = {
 *                      {
 *                          "name" = "date[after]",
 *                          "in" = "query",
 *                          "description" = "Start date",
 *                          "required" = true,
 *                          "type" = "string",
 *                          "format"= "date"
 *                      },
 *                      {
 *                          "name" = "date[before]",
 *                          "in" = "query",
 *                          "description" = "End date",
 *                          "required" = true,
 *                          "type" = "string",
 *                          "format"= "date"
 *                      }
 *                  }
 *               }
 *          }
 *     }
 *
 * )
 *
 * @ApiFilter(DateFilter::class, properties={"date"}))
 * @ApiFilter(SearchFilter::class, properties={"city": "exact", "city.name": "exact"}))
 * @ApiFilter(RangeFilter::class, properties={"temp"}))
 * @ApiFilter(OrderFilter::class, properties={"date": "DESC"}))
 *
 *
 *
 */
class WeatherData
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity=City::class))
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id"))
     *
     */
    private $city;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $date;

    /**
     * @ORM\Column(type="float")
     * @Groups({"weather:read"})
     *
     */
    private $temp;

    /**
     * @ORM\Column(type="float")
     */
    private $minTemp;

    /**
     * @ORM\Column(type="float")
     */
    private $maxTemp;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @SerializedName("city")
     * @Groups({"weather:read"})
     */
    public function getCityName()
    {
        return $this->city? $this->city->getTitle() : '';
    }

    /**
     * @SerializedName("country")
     * @Groups({"weather:read"})
     */
    public function getCountryName()
    {
        return $this->city? $this->city->getCountryName() : '';
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): self
    {
        $this->date = $date;
        return $this;
    }
    /**
     * @SerializedName("date")
     * @Groups({"weather:read"})
     */
    public function getDateString()
    {
        return $this->date ? $this->date->format('Y-m-d') : '';
    }

    public function getTemp(): ?float
    {
        return $this->temp;
    }

    public function setTemp(float $temp): self
    {
        $this->temp = $temp;
        return $this;
    }

    public function getMinTemp(): ?float
    {
        return $this->minTemp;
    }

    public function setMinTemp(float $temp): self
    {
        $this->minTemp = $temp;
        return $this;
    }


    public function getaMxTemp(): ?float
    {
        return $this->maxTemp;
    }

    public function setMaxTemp(float $temp): self
    {
        $this->maxTemp = $temp;
        return $this;
    }

}
