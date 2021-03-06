<?php

namespace AppBundle\Repository;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

/**
 * DriverRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SettlementRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Pobiera dane potrzebne do wydrukowania potwierdzenia rozliczenia transakcji
     * @param $cashRegisterId
     * @return array
     * @throws DBALException
     */
    public function printSettledTransaction($cashRegisterId)
    {
        $sql = "SELECT
                  r.cash_register_number cashRegisterNumber, 
                  date_format(r.transaction_date,'%Y-%m-%d') cashRegisterDate,
                  ts.totalAmountWithFee, r.title, p.name,
                  date_format(t.transaction_date,'%Y-%m-%d') transactionDate, s.settled_amount settledAmount,
                  s.amount_with_fee amountWithFee, t.transaction_type transactionType, c.name client,
                  concat(d.first_name,' ',d.surname) driver, d.license_number licenseNumber,
                  u.name user
                FROM settlement s
                JOIN transaction t ON s.transaction_id = t.id
                LEFT JOIN cash_register r ON s.cash_register_id = r.id
                LEFT JOIN cash_register_detail rd ON r.id = rd.cash_register_id
                LEFT JOIN user u ON r.user_id = u.id
                LEFT JOIN param p ON rd.param_id = p.id
                LEFT JOIN client c ON t.client_id = c.id
                LEFT JOIN driver d ON t.driver_id = d.id
                LEFT JOIN (
                  SELECT sum(amount_with_fee) totalAmountWithFee, cash_register_id
                  FROM settlement
                  WHERE cash_register_id = :cashRegisterId GROUP BY cash_register_id
                  ) ts ON r.id = ts.cash_register_id
                WHERE 1=1
                  AND s.cash_register_id = :cashRegisterId";

        $pdo = $this->getEntityManager()->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':cashRegisterId' => $cashRegisterId
        ]);
        $result = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC))
        {
            $result['cashRegisterNumber'] = $row['cashRegisterNumber'];
            $result['cashRegisterDate'] = $row['cashRegisterDate'];
            $result['totalAmountWithFee'] = $row['totalAmountWithFee'];
            $result['title'] = $row['title'];
            $result['type'] = $row['name'];
            $result['driver'] = $row['driver'];
            $result['licenseNumber'] = $row['licenseNumber'];
            $result['user'] = $row['user'];

            $transaction['transactionDate'] = $row['transactionDate'];
            $transaction['settledAmount'] = $row['settledAmount'];
            $transaction['amountWithFee'] = $row['amountWithFee'];
            $transaction['transactionType'] = $row['transactionType'];
            $transaction['client'] = $row['client'];
            $result['transactions'][] = $transaction;
        }
        return $result;
    }
}
