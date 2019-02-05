<?php

namespace AppBundle\Command;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use AppBundle\Service\ImportTransaction;

class BackgroundImportCommand extends Command
{
    protected static $defaultName = 'transaction:import';

    protected function configure()
    {
        $this->addArgument('fileName', InputArgument::REQUIRED);
        $this->addArgument('extension', InputArgument::REQUIRED);
    }

    public function __construct(RegistryInterface $doctrine, UserPasswordEncoderInterface $encoder)
    {
        parent::__construct();
        $this->doctrine = $doctrine;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $filename = $input->getArgument('fileName');
        $extension = $input->getArgument('extension');
        $importTransaction = new ImportTransaction($this->doctrine);

        switch ($extension){
            case 'xml':
                $importTransaction->readXmlFile($filename);
                break;
            case 'csv':
                $importTransaction->readCsvFile($filename);
                break;
            case 'xls':
                $importTransaction->readXlsFile($filename);
                break;
        }

    }

}
