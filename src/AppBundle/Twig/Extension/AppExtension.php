<?php

namespace AppBundle\Twig\Extension;

use AppBundle\Entity\CashRegister\CashRegister;
use AppBundle\Entity\CashRegister\CashRegisterReport;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AppExtension extends \Twig_Extension
{
    /**
     * @var RegistryInterface
     */
    private $doctrine;
    /**
     * @var TokenStorageInterface
     */
    private $storage;

    /**
     * AppExtension constructor.
     * @param RegistryInterface $doctrine
     * @param TokenStorageInterface $storage
     */
    public function __construct(RegistryInterface $doctrine, TokenStorageInterface $storage)
    {
        $this->doctrine = $doctrine;
        $this->storage = $storage;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app';
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('cashRegisterStatus', [$this, 'getCashRegisterStatus'], [

            ])
        ];
    }

    public function getCashRegisterStatus()
    {
        $repo = $this->doctrine->getRepository(CashRegister::class);
        $row = $repo->getCashRegisterStatus($this->storage->getToken()->getUser()->getId());

        return sprintf(" %s zł/%s", $row['amount'], $row['reportForDate']) ;
    }
}
