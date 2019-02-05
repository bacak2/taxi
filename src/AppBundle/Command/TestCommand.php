<?php

namespace AppBundle\Command;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends Command
{
    protected static $defaultName = "app:919";
    /**
     * @var RegistryInterface
     */
    private $doctrine;

    /**
     * TestCommand constructor.
     */
    public function __construct(RegistryInterface $doctrine)
    {
        parent::__construct();
        $this->doctrine = $doctrine;
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pdo = $this->doctrine->getConnection('taxi919');
        $sql = "select name from t_Klient";

        $stmt = $pdo->query($sql);

        print_r($stmt->fetch(\PDO::FETCH_ASSOC));

        $output->writeln('OK');
    }
}