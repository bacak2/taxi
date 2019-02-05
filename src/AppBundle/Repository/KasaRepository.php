<?php

namespace AppBundle\Repository;

use Symfony\Bridge\Doctrine\RegistryInterface;

class KasaRepository
{
    /** @var \PDO $pdo */
    private $pdo;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->pdo = $doctrine->getConnection('taxi919');
    }

    public function getDrivers()
    {
        $sql = "SELECT
                    LicenseNumber, CabSideNumber,
                    FirstName, Surname,
                    Email,
                    AddressStreet, AddressPostalCode, AddressTown, AddressCountry,
                    AccountNumber,
                    AccountOwner,
                    AccountAddressStreet,
                    AccountAddressPostalCode,
                    AccountAddressTown,
                    AccountAddressCountry,
                    NIP,
                    InvoiceGoodsReferenceDesc, InvoiceGoodsReferenceVAT,
                    blokada_wyplat,
                    blokada_data,
                    blokada_opis,
                    blokada_powod,
                    bank_posid,
                    IsVirtual,
                    notes,
                    PlatnikVAT,
                    NazwaFirmy,
                    CzyFakturowac,
                    CzyBagazowka,
                    PlatnikVATStawka,
                    CzyFakturowacSkupProwizje,
                    PassWWW,
                    Removed,
                    CzyPrzelewOkresowy,
                    CzyDarmowyPrzelew
                FROM t_Kierowca
                WHERE 1=1";

        $rows = $this->pdo->query($sql);
        $result = [];
        while ($row = $rows->fetch(\PDO::FETCH_ASSOC)){
            $result[$row['CabSideNumber']]  = $row;
        }

        return $result;
    }
}