<?php

namespace AppBundle\Command;

use AppBundle\Entity\User;
use AppBundle\Service\Taxi360Service;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Taxi360Command extends Command
{
    protected static $defaultName = 'taxi360:call';

    private $statusUrl = 'http://panel.taxi360.pl/Interface/RADIOTAXI919/Test?ChannelName=TEST_919&ChannelPassword=!919&RequestType=STATUS_REQ';

    private $resetUrl = 'http://panel.taxi360.pl/Interface/RADIOTAXI919/Test?ChannelName=TEST_919&ChannelPassword=!919&RequestType=REINITIALIZE';

    private $dataUrl = 'http://panel.taxi360.pl/Interface/RADIOTAXI919/Test?ChannelName=TEST_919&ChannelPassword=!919&RequestType=DATA_REQ';

    /**
     * @var ClientInterface
     */
    private $client;
    /**
     * @var Taxi360Service
     */
    private $taxi360;
    /**
     * @var RegistryInterface
     */
    private $doctrine;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(Client $client, Taxi360Service $taxi360,
                                RegistryInterface $doctrine, LoggerInterface $logger)
    {
        parent::__construct();
        $this->client = $client;
        $this->taxi360 = $taxi360;
        $this->doctrine = $doctrine;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->addOption('info',null,InputOption::VALUE_NONE,"Info z kanału", null)
            ->addOption('restart', null, InputOption::VALUE_NONE, "Restart interface", null)
            ->setDescription('Pobiera pakiet z zewnętrznego API');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $optionInfo    = $input->getOption('info');
        $optionRestart = $input->getOption('restart');
        if($optionInfo)
        {
            try {

                $response = $this->client->request('POST', $this->statusUrl);
                $output->writeln($response->getBody()->getContents());
            } catch (GuzzleException $e) {
                $output->writeln($e->getMessage());
            }
        }elseif($optionRestart)
        {
            //$response = $this->client->request('POST', $this->resetUrl);
            //echo $response->getBody();
            $output->writeln('Resest wyłączony!');
        }
        else{

            $i = 0;
            gc_enable();
            $data  = true;
            while ($data){
                try {

                    $response = $this->client->request('POST', $this->dataUrl);
                    $data = $response->getBody()->getContents();
                    $this->logger->notice($data);
                    if($response->getBody()->getSize() === 0)
                    {
                        $data = false;
                        $output->writeln("Brak pakietów do pobrania");
                    }else{
                        $result = $this->taxi360->importData($data, true);
                        $output->writeln($response->getStatusCode());
                        $output->writeln("Sequence ID: " . $result['sequence']);
                    }
                    $i++;
                } catch (GuzzleException $e) {
                    $output->writeln($e->getMessage());
                }
                gc_collect_cycles();
            }
        }
    }
}
