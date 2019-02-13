<?php

namespace AppBundle\Service;


use AppBundle\Entity\Form\SettingsFormData;
use AppBundle\Entity\Params\Param;
use AppBundle\Entity\Params\TaxiSettings;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TaxiSettingsService
{
    /**
     * @var RegistryInterface
     */
    private $doctrine;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getFormData(){

        $taxiRepo = $this->doctrine->getRepository(TaxiSettings::class);
        $paramsRepo = $this->doctrine->getRepository(Param::class);

        $settings = $taxiRepo->find(1);
        if($settings == null){
            $settings = new TaxiSettings();
        }

        $settingsFormData = new SettingsFormData();
        $settingsFormData
            ->setAmericanExpress($settings->getAmericanExpress())
            ->setVisaMasterCard($settings->getVisaMasterCard())
            ->setCard($settings->getCard())
            ->setVoucher($settings->getVoucher())
            ->setEVoucher($settings->getEVoucher())
            ->setBankTransferCost($settings->getBankTransferCost())
            ->setBankName($settings->getBankName())
            ->setBankAccount($settings->getBankAccount())
            ->setSwift($settings->getSwift())
            ->setFreeTransferBankAccount($settings->getFreeTransferBankAccount())
            ->setVat($settings->getVat())
            ->setDaysToPay($settings->getDaysToPay())
            ->setParams($paramsRepo->findAll());

        return $settingsFormData;
    }

    public function addDictionaryParam($params){
        $sql = "INSERT INTO dictionary(category_id, user_id, value, editable) VALUES (:id, 1, :value, 1)";
        /** @var \PDOStatement $stmt */
        $stmt = $this->doctrine->getConnection()->prepare($sql);
        $stmt->execute(array(
            ':id' => $params[0]['value'],
            ':value' => $params[1]['value']
        ));
    }
}