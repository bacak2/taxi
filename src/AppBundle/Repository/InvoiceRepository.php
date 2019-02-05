<?php

namespace AppBundle\Repository;
use AppBundle\Service\HelperService;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use PDO;

/**
 * DriverRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class InvoiceRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Funkcja zwracająca faktury w module Opłaty i skłądki
     */
    public function findInvoiceByDriverId($id)
    {
        $sql = "SELECT
                  i.invoice_year, i.invoice_month, i.invoice_number, i.amount_brutto, 
                  'doc_type' as document_type,'type' as invoice_type,
                  d.title, d.vat, d.amount_brutto
                FROM invoice i
                  LEFT JOIN invoice_detail d ON i.id = d.invoice_id
                WHERE 1=1
                  AND (seller = :id or buyer = :id)";
        $pdo = $this->getEntityManager()->getConnection();
        $rows = $pdo->prepare($sql);
        $rows->execute([
            ':id' => $id
        ]);
        $result = [];
        while ($row = $rows->fetch(PDO::FETCH_ASSOC))
        {
            $result[] = $row;
        }

        return $result;
    }
}
