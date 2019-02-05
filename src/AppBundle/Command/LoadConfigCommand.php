<?php

namespace AppBundle\Command;

use AppBundle\Entity\Params\Param;
use AppBundle\Entity\Params\TaxiSettings;
use AppBundle\Entity\Params\ParamCategory;
use AppBundle\Entity\User;
use PDO;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LoadConfigCommand extends Command
{
    protected static $defaultName = 'params:load';
    /**
     * @var RegistryInterface
     */
    private $doctrine;

    /** @var PDO */
    private $pdo;

    protected function configure()
    {
        $this
            ->addOption('dictionary', 'd', InputOption::VALUE_NONE)
            ->addOption('application', 'a', InputOption::VALUE_NONE,
                'Ładuje domyślne ustawienia dla aplikacji');
    }

    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
        $this->pdo = $doctrine->getConnection();
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if($input->getOption('dictionary'))
        {
            $this->loadDictionary();
            $output->writeln('Wgrano ustawienia słownika!');
        }
        if($input->getOption('application') )
        {
            $this->loadApplicationSettings();
            $output->writeln('Wgrano ustawienia aplikacji!');
        }

        $output->writeln('Koniec');
    }

    protected function loadApplicationSettings()
    {
        $params = $this->doctrine->getRepository(TaxiSettings::class)
            ->find(1);
        if($params == NULL)
        {
            $params = new TaxiSettings();
        }
        $params->setId(1)
            ->setAmericanExpress(0.02)
            ->setVisaMasterCard(0.012)
            ->setCard(0.10)
            ->setVoucher(0.10)
            ->setEVoucher(0.10)
            ->setBankAccount('61861900060090090001420001')
            ->setBankAccountToFirm('61861900060090090001420001')
            ->setBankName('Małopolski Bank Spółdzielczy')
            ->setSwift('POLUPLPR')
            ->setVat(0.23)
            ->setDaysToPay(14)
            ->setBankTransferCost(0.62);

        $this->doctrine->getManager()->persist($params);
        $this->doctrine->getManager()->flush();
    }

    protected function loadDictionary()
    {
//        $sql = "SET FOREIGN_KEY_CHECKS=0;TRUNCATE param_category;TRUNCATE param;SET FOREIGN_KEY_CHECKS=1";
//        $this->pdo->exec($sql);

        $user = $this->doctrine->getRepository(User::class)
            ->findOneBy(['username' => 'system']);

        $userCategoryList = array(
            // 2
            "Rodzaje opłat" => array(
                "Abonament: Usługa transportowa",
                "Abonament: Rezerwacja usług taksówkowych",
                "Abonament: Konserwacja sprzętu RTF i PC",
                "Telefon: Opłata za telefon",
                "Wpisowe",
                "Zmiana układu taryf",
                "Terminal: Opłata za dzierżawę terminala",
                "Usługa programowania taksometru"
            ),
            //3
            "Towar KP" => array(
                "Materiały - kierowca",
                "Opłaty należne spółce",
                "Zasilenie kasy z banku",
                "Zaliczka na wynagrodzenie",
                "Kaucja",
                "Ładowarka",
                "Kalendarz",
                "Bateria",
                "Emblematy",
                "Antena",
                "Bloczki",
                "Rachunki",
                "Magnes",
                "Wpisowe",
                "Bateria do telefonu",
                "Rata za radio",
                "Ładowarka do telefonu",
                "Ekran",
                "Wypłata LP Pracownicy",
                "Kara umowna",
                "Dopłata zgodnie z Uchwałą NZW z dn. 27.09.2014",
                "Abonament - kierowca",
                "Kabel manipulacyjny",
                "Pręt anteny",
                "Guma",
                "Faktury",
                "Vouchery",
                "Infolinia",
                "Galeria Krakowska",
                "Zakopianka",
                "Willa Decjusza",
                "Cennik pojedyńczy",
                "Cennik dwustronny",
                "Logo RT 919",
                "Herb",
                "Plan Miasta Krakowa",
                "Scanmed magnes",
                "Scanmed",
                "Przelotka terminala",
                "Płatność kartą",
                "Kategoria NIEOZNACZONA",
                "Karta Płyta Centrum",
                "Wymiana oświetlenia w kogucie",
                "Cyfra na herb"
            ),
            "Rodzaje KP" => array(
                "Dzierżawa GPRS",
                "Materiały - kierowca",
                "Kategoria NIEOZNACZONA",
                "Opłaty należne spółce",
                "Paragony",
                "Raty - spłaty kierowców",
                "Szkolenie",
                "Terminal",
                "Wyposażenie",
                "Zasilenie kasy z banku",
                "Zaliczka na wynagrodzenie"
            ),
            //5
            "Rodzaje KW" => array(
                "Delegacja",
                "Należne wpłaty na kapitał",
                "Faktura VAT kosztowa",
                "Karta Płyta Centrum",
                "Kaucja",
                "KATEGORIA NIEOZNACZONA",
                "Wypłata z kasy do banku",
                "Umożenie udziałów",
                "Wypłata: LP pracownicy",
                "Zaliczka na wynagrodzenie"
            ),
            //13
            "Rodzje KW bezgotowka" => array(
                "Karty bankowe","Karty własne","Karty bankowe i własne"
            )
        );

        foreach ($userCategoryList as $category => $items)
        {
            $cat = new ParamCategory();
            $cat->setName($category);

            foreach ($items as $item)
            {
                $param = new Param();
                $param->setName($item)
                    ->setUser($user);

                $cat->addParam($param);
            }
            $this->doctrine->getManager()->persist($cat);
        }
        $this->doctrine->getManager()->flush();
    }
}
