<?php

namespace AppBundle\Repository;
use AppBundle\Entity\CashRegister\CashRegister;
use AppBundle\Entity\CashRegister\CashRegisterReport;
use AppBundle\Entity\User;
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
class CashRegisterRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Lista dodanych KP oraz KW
     * @param array $params
     * @return array
     * @throws DBALException
     */
    public function getListByDateAndType($params = array())
    {
        $pdo = $this->getEntityManager()->getConnection();
        $sql = "SELECT
                c.id, count(c.id) items, c.transaction_type transactionType,
                date_format(c.transaction_date,'%Y-%m-%d') transactionDate,
                c.cash_register_number cashRegisterNumber, c.is_settlement isSettlement, 
                ifnull(concat(d.first_name,' ',d.surname), f.name) as recipient, c.title,
                p.name itemName, sum(cd.brutto) as amount,
                a.username as user
            FROM cash_register c
                LEFT JOIN cash_register_detail cd ON c.id = cd.cash_register_id 
                LEFT JOIN driver d ON c.driver_id = d.id
                LEFT JOIN client f ON c.client_id = f.id
                LEFT JOIN user a ON c.user_id = a.id
                LEFT JOIN param p ON cd.param_id = p.id
            WHERE 1=1
                AND c.transaction_type = :type
                AND date(transaction_date) >= :dateFrom
                AND date(transaction_date) <= :dateTo
            GROUP BY c.id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':dateFrom' => $params['dateFrom'],
            ':dateTo' => $params['dateTo'],
            ':type' => $params['type']
        ));

        $rows = array();
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC))
        {
//            $row['is_without_cash'] = ($row['is_without_cash'] == 1)
//                ? "<i class='large green checkmark icon'></i>" : "";
//            $row['withDetails'] = ($row['withDetails'] == 1) ? true : false;
            $row['items'] = ($row['items'] > 1) ? 1 : 0;
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * Dane do drukowania KP - magazyn
     * @param $id
     * @return array|null
     * @throws DBALException
     */
    public function printKPWarehouseData($id)
    {
        $sql = "SELECT
              c.cash_register_number cashRegisterNumber, date_format(c.transaction_date,'%Y-%m-%d') transactionDate, 
              concat(d.first_name,' ',d.surname) driver, d.license_number licenseNumber,
              u.username user, c.title, p.name itemName, 
              detail.netto, round(detail.vat*100,1) vat, detail.brutto, detail.quantity
              FROM cash_register c
              LEFT JOIN cash_register_detail detail ON c.id = detail.cash_register_id
              LEFT JOIN param p ON detail.param_id = p.id
              LEFT JOIN user u ON c.user_id = u.id
              LEFT JOIN driver d ON c.driver_id = d.id
            WHERE 1=1
              AND c.id = :id";

        $pdo = $this->getEntityManager()->getConnection();
        $stmt = $pdo->prepare($sql);
        try{
            $stmt->execute([
                ":id" => $id
            ]);
        }catch(\Exception $e){
            return $result = null;
        }

        $result = [];
        $result['brutto'] = 0;
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC))
        {
            $result['cashRegisterNumber'] = $row['cashRegisterNumber'];
            $result['transactionDate'] = $row['transactionDate'];
            $result['driver'] = $row['driver'];
            $result['licenseNumber'] = $row['licenseNumber'];
            $result['user'] = $row['user'];
            $result['title'] = $row['title'];
            $result['brutto'] += $row['brutto'];

            $details['itemName'] = $row['itemName'];
            $details['netto'] = $row['netto'];
            $details['vat'] = $row['vat'];
            $details['brutto'] = $row['brutto'];
            $details['quantity'] = $row['quantity'];

            $result['details'][] = $details;
        }

        return $result;
    }

    public function printKPStandard($id)
    {
        $sql = "SELECT
                  c.cash_register_number cashRegisterNumber, 
                  date_format(c.transaction_date,'%Y-%m-%d') transactionDate, 
                  cd.brutto amount, concat(d.first_name,' ',d.surname) driver, 
                  d.license_number licenseNumber,
                  u.username user, p.name itemName, c.title,
                  cd.netto, round(cd.vat*100,1) vat, cd.brutto, cd.quantity
              FROM cash_register c
              LEFT JOIN cash_register_detail cd ON c.id = cd.cash_register_id
              LEFT JOIN param p ON cd.param_id = p.id
              LEFT JOIN user u ON c.user_id = u.id
              LEFT JOIN driver d ON c.driver_id = d.id
            WHERE 1=1
              AND c.id = :id";

        $pdo = $this->getEntityManager()->getConnection();
        $stmt = $pdo->prepare($sql);
        try{
            $stmt->execute([
                ":id" => $id
            ]);
        }catch(DBALException $e){
            die($e->getMessage());
        }

        $result = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC))
        {
            $result = $row;
        }
        return $result;
    }

    public function printKWStandard($id)
    {
        $sql = "SELECT
                  c.cash_register_number cashRegisterNumber, 
                  date_format(c.transaction_date,'%Y-%m-%d') transactionDate, 
                  concat(d.first_name,' ',d.surname) driver, 
                  ifnull(concat(d.first_name,' ',d.surname), c2.name) as recipient, 
                  d.license_number licenseNumber,
                  u.username user, p.name itemName, c.title, cd.brutto amount, cd.quantity
              FROM cash_register c
              LEFT JOIN cash_register_detail cd ON c.id = cd.cash_register_id
              LEFT JOIN param p ON cd.param_id = p.id
              LEFT JOIN user u ON c.user_id = u.id
              LEFT JOIN driver d ON c.driver_id = d.id
              LEFT JOIN client c2 ON c.client_id = c2.id
            WHERE 1=1
              AND c.id = :id";

        $pdo = $this->getEntityManager()->getConnection();
        $stmt = $pdo->prepare($sql);
        try{
            $stmt->execute([
                ":id" => $id
            ]);
        }catch(DBALException $e){
            die($e->getMessage());
        }

        $result = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC))
        {
            $result = $row;
        }
        return $result;
    }

    /**
     * Dane do wygenerowania raportu z kasy
     */
    public function getCashRegisterList(\DateTime $dateTime, $period, User $user)
    {
        $rows = [];
        $rows['prev'] = 0;
        $rows['curr'] = 0;
        $rows['change'] = 0;
        $rows[CashRegister::TYPE_KW]['count'] = 0;
        $rows[CashRegister::TYPE_KW]['amount'] = 0.0;
        $rows[CashRegister::TYPE_KP]['count'] = 0;
        $rows[CashRegister::TYPE_KP]['amount'] = 0.0;
        $rows['settlements'] = [];
        $rows['cashReportId'] = null;

        /**
         * @var \PDO $pdo
         */
        $pdo = $this->getEntityManager()->getConnection();
        $prevReport = $this->getCashRegisterStatus($user->getId(), $dateTime);
        if($prevReport != false)
        {
            $rows['prev'] = (float)$prevReport['amount'];
        }

        $sql = "SELECT
                  c.report_id cashReport, c.monthly_report_id monthlyReport,
                  c.id, c.cash_register_number cashRegisterNumber, c.transaction_date cashboxDate,
                  c.title, c.transaction_type transactionType,
                  ifnull(concat(d2.license_number,' ',d2.first_name, ' ', d2.surname), c2.name) recipient,
                  sum(cd.brutto) amount,
                  p.name item,
                  u.username user, u.position
                FROM cash_register c
                  LEFT JOIN cash_register_detail cd ON c.id = cd.cash_register_id
                  LEFT JOIN user u ON c.user_id = u.id
                  LEFT JOIN param p ON cd.param_id = p.id
                  LEFT JOIN driver d2 ON c.driver_id = d2.id
                  LEFT JOIN client c2 ON c.client_id = c2.id
                WHERE 1=1
                  AND u.id = :userId";
        $execParams = [
            ':userId' => $user->getId()
        ];

        if($period == CashRegisterReport::DAILY_REPORT)
        {
            $sql .= " AND date_format(c.transaction_date,'%Y-%m-%d') = :date";
            $execParams[':date'] = $dateTime->format('Y-m-d');
        }else{
            $sql .= " AND date_format(c.transaction_date,'%m')= :transactionMonth
                AND date_format(c.transaction_date, '%Y') = :transactionYear ";

            $execParams[':transactionMonth'] = $dateTime->format('m');
            $execParams[':transactionYear'] = $dateTime->format('Y');
        }
        $sql .= " GROUP BY c.id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($execParams);

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC))
        {
            if($row['transactionType'] == 'kp')
            {
                $rows['change'] += (float)$row['amount'];
            }else{
                $rows['change'] -= (float)$row['amount'];;
            }
            $rows[$row['transactionType']]['count']++;
            $rows[$row['transactionType']]['amount'] += (float)$row['amount'];
            $rows['settlements'][] = $row['id'];

            if($period == CashRegisterReport::DAILY_REPORT)
            {
                $rows['cashReportId'] = $row['cashReport'];
            }else{
                $rows['cashReportId'] = $row['monthlyReport'];
            }


            $rows[$row['transactionType']]['items'][] = $row;
        }

        $rows['curr'] = $rows['prev'] + $rows['change'];

        return $rows;
    }

    public function getCashRegisterStatus($user, \DateTime $dateTime = null)
    {
        $sqlIn = "SELECT max(report_for_date) maxDate
                FROM cash_register_report
                WHERE 1=1
                  AND user_id = :userId";

        if($dateTime != null){
            $sqlIn .= " AND report_for_date < :dateParam";
        }

        $sql = "SELECT
                id, user_id user, report_type reportType, report_number reportNumber,
                report_for_date reportForDate,
                kp_count kpCount, kp_amount kpAmount, kw_count kwCount, kw_amount kwAmount,
                amount, prev_amount prevAmount, change_amount changeAmount
            FROM cash_register_report c
              JOIN (".$sqlIn.") t ON c.report_for_date = t.maxDate
            WHERE 1=1
                  AND c.user_id = :userId";

        if($dateTime != null){
            $sql .= " AND c.report_for_date < :dateParam";
            $execParam[':dateParam'] = $dateTime->format('Y-m-d');
        }
        $execParam[':userId'] = $user;

        $pdo = $this->getEntityManager()->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($execParam);


        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
