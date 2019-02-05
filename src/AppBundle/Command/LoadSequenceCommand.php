<?php

namespace AppBundle\Command;

use AppBundle\Entity\SequenceDB;
use AppBundle\Entity\User;
use AppBundle\Service\Taxi360Service;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LoadSequenceCommand extends Command
{
    protected static $defaultName = 'sequence:load';
    /**
     * @var RegistryInterface
     */
    private $doctrine;
    /**
     * @var Taxi360Service
     */
    private $taxi360;

    public function __construct(RegistryInterface $doctrine, Taxi360Service $taxi360)
    {
        parent::__construct();
        $this->doctrine = $doctrine;
        $this->taxi360 = $taxi360;
    }

    protected function configure()
    {
        $this
            ->addOption('sequence','s', InputOption::VALUE_REQUIRED)
            ->addOption('all', 'a', InputOption::VALUE_NONE)
            ->addArgument('range', InputArgument::IS_ARRAY)
            ->setDescription('Hello PhpStorm');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {


        $repo = $this->doctrine->getRepository(SequenceDB::class, 'taxi_data');
        $seqRange = $input->getArgument('range');
        if(count($seqRange) > 1){
            $from = $seqRange[0];
            $to = $seqRange[1];

            while ($from <= $to){

                $sequence = $repo->find($from);
                if($sequence != NULL)
                {
                    $result = $this->taxi360->importData($sequence->getContent(), false);
                    $output->writeln('Zakończono import pakietu '.$result['sequence']);
                }

                $from++;

            }
        }

        if($input->getOption('sequence'))
        {
            $sequence = $repo->find($input->getOption('sequence'));
            if($sequence != NULL)
            {
                $result = $this->taxi360->importData($sequence->getContent(), false);
                $output->writeln('Zakończono import pakietu '.$result['sequence']);
            }else{
                $output->writeln("Nie ma takiego pakietu");
            }
        }
//        if($input->getOption('all'))
//        {
//            $sequences = $repo->findAll([],[
//                "sequence_id" => "asc"
//            ]);
//            $i = 1;
//            foreach ($sequences as $sequence)
//            {
//                $result = $this->getContainer()->get('app.service.api_taxi_360')->importData($sequence->getContent());
//                $output->writeln( $i .': Zakończono import');
//                $i++;
//            }
//            $output->writeln('Zaladowano wszystkie ustawienia!');
//        }
    }
}
