<?php

namespace AppBundle\Repository;


use AppBundle\Entity\Params\Param;
use AppBundle\Entity\Params\TaxiSettings;
use Symfony\Bridge\Doctrine\RegistryInterface;

class SettingsRepository
{
    /**
     * @var RegistryInterface
     */
    private $doctrine;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }


}