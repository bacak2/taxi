<?php

namespace AppBundle\Command;

use AppBundle\Entity\ApiTaxi360\Driver;
use AppBundle\Repository\KasaRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class KasaCommand extends Command
{
    protected static $defaultName = 'kasa:query';
    /**
     * @var KasaRepository
     */
    private $kasaRepo;
    /**
     * @var RegistryInterface
     */
    private $doctrine;

    public function __construct(KasaRepository $kasaRepo, RegistryInterface $doctrine)
    {
        parent::__construct();
        $this->kasaRepo = $kasaRepo;
        $this->doctrine = $doctrine;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $driverRepo = $this->doctrine->getRepository(Driver::class);
        $drivers = $this->kasaRepo->getDrivers();
        $manager = $this->doctrine->getEntityManager();

        foreach ($drivers as $item)
        {
            /** @var Driver $driver */
            $driver = $driverRepo->findOneBy(['cabSideNumber' => $item['CabSideNumber']]);
            if($driver == null){
                continue;
            }
            $driver
                ->setEmail($item['Email'])
                ->setAddressStreet($item['AddressStreet'])
                ->setAddressPostalCode($item['AddressPostalCode'])
                ->setAddressTown($item['AddressTown'])
                ->setAccountNumber($item['AccountNumber'])
                ->setAccountOwner($item['AccountOwner'])
                ->setPosNumberMid($item['bank_posid'])
                ->setFirmName($item['NazwaFirmy'])
                ->setInternetPassword($item['PassWWW'])
                ->setNip($item['NIP'])
                ;
            $manager->persist($driver);
        }
        $manager->flush();
        $output->writeln('Aktualizacja zakonczona');

    }
}