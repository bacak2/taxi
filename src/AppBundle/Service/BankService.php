<?php /** @noinspection ALL */

namespace AppBundle\Service;


use Symfony\Bridge\Doctrine\RegistryInterface;

class BankService
{
    /**
     * @var RegistryInterface
     */
    private $doctrine;

    private $transferCost = 0.65;

    /**
     * @todo get transfer cost from settings
     */
    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function calculate($formData, $generateTransactions = false)
    {
        $params = $this->convertFormData($formData);
        $periodic = $this->getPeriodicCondition($params);
        $transactionType = $this->getTransactionTypeCondition($params);
//        $params['dateFrom'] = '2018-11-12';
//        $params['dateTo'] = '2018-11-12';

        //        toSettlement;blocked
        //        4399.20;0
        //        54.00;1

        $sql = sprintf("SELECT
                  t.transaction_date,
                  t.is_settled,d.license_number,
                  d.first_name, d.surname, d.firm_name,
                  d.account_owner, d.account_number, d.blocked,
                  d.vat, d.vat_payer, d.free_money_transfer,
                  s.total_amount,
                  s.percentage,
                  round(s.total_amount - (s.total_amount*s.percentage),2) as driver_amount
                FROM transaction t
                JOIN settlement s ON t.id = s.transaction_id
                JOIN driver d ON t.driver_id = d.id
                WHERE 1=1
                  AND t.transaction_status = 'ACCEPTED'
                  %s %s %s %s %s
                  -- AND d.account_number <> ''
                  AND s.is_settled = 0
                ",
            ($params['dateFrom'] != null) ? " AND t.transaction_date >= '".$params['dateFrom']."'" : null,
            ($params['dateTo'] != null) ? " AND t.transaction_date <= '".$params['dateTo']." 23:59:59'" : null,
            ($params['driver'] != null) ? " AND d.id = " . $params['driver'] : null,
            " AND d.periodic_transfer in({$periodic})",
            " AND t.transaction_type in ({$transactionType})"
            );



        /** @var \PDO $pdo */
        $pdo = $this->doctrine->getConnection();
        $rows = $pdo->query($sql);
        $result = [];
        $result['forSettlement']['amount'] = 0;
        $result['forSettlement']['description'] = 'Do rozliczenia';
        $result['blocked']['amount'] = 0;
        $result['blocked']['description'] = 'Kwota zablokowana';
        $result['noAccount']['amount'] = 0;
        $result['noAccount']['description'] = 'Brak numeru konta';
        $drivers = [];
        if($generateTransactions) $result['transactions'] = [];

        while ($row = $rows->fetch(\PDO::FETCH_ASSOC))
        {
            /** Czy wpisany jest numer konta */
            if($row['account_number'] == '')
            {
                $result['noAccount']['amount'] += $row['driver_amount'];
            }else{
                if($row['blocked'] == 1){
                    $result['blocked']['amount'] += $row['driver_amount'];
                }else{
                    /** Czy są darmowe przelewy */
                    if($row['free_money_transfer'] == 1){
                        $result['forSettlement']['amount'] += $row['driver_amount'];
                    }else{
                        /** Czy kierowca ma już policzony koszt przelewu */
                        if(!isset($drivers[$row['license_number']])){
                            $drivers[$row['license_number']] = 1;
                            $result['forSettlement']['amount'] += ($row['driver_amount'] - $this->transferCost);
                        }else{
                            $result['forSettlement']['amount'] += $row['driver_amount'];
                        }
                    }
                }
            }

            if($generateTransactions) array_push($result['transactions'], $row);

        }

        return $result;
    }

    protected function convertFormData($formData)
    {
        $result = [
            'dateFrom' => null,
            'dateTo' => null,
            'driver' => null,
            'bezgotowka' => null,
            'importpko' => null,
            'periodic' => null,
            'nonPeriodic' => null
        ];
        foreach ($formData as $item){
            $key = sscanf($item['name'], 'bank[%[^]]')[0];
            if(array_key_exists($key, $result) && $item['value'] != ''){
                $result[$key] = $item['value'];
            }else{
                $result[$key] = null;
            }
        }

        return $result;
    }

    protected function getTransactionTypeCondition($formData)
    {
        $string = '';
        if(isset($formData['importpko']) && $formData['importpko'] != null){
            $string .= "'importpko',";
        }
        if(isset($formData['bezgotowka']) && $formData['bezgotowka'] != null){
            $string .= "'bezgotowka'";
        }
        $string = rtrim($string, ",");

        return $string;
    }

    protected function getPeriodicCondition($formData)
    {
        $string = [];
        if(isset($formData['periodic']) && $formData['periodic'] != null){
            $string[] = 1;
        }
        if(isset($formData['nonPeriodic']) && $formData['nonPeriodic'] != null){
            $string[] = 0;
        }

        return implode(',', $string);
    }
}