<?php

namespace AppBundle\Service;

use AppBundle\Entity\Enumerator;
use AppBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Annotations\Annotation\Enum;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class EnumManager
{
//    /**
//     * @var Registry $doctrine
//     */
//    private $doctrine;
//
//    /**
//     * @var User $user
//     */
//    private $user;
//
//    /**
//     * EnumManager constructor.
//     * @param RegistryInterface $doctrine
//     */
//    public function __construct(RegistryInterface $doctrine, TokenStorageInterface $storage)
//    {
//        $this->doctrine = $doctrine;
//        $this->user = $storage->getToken()->getUser();
//    }
//
//    /**
//     * Zwraca kolejny numer dla dokumentu kasowego
//     * @param $type
//     * @return array
//     */
//    public function getCashRegisterNumber($type)
//    {
//        /**
//         * @var Enumerator $enumerator
//         */
//        $enumerator = $this->doctrine
//            ->getRepository(Enumerator::class)->getNumber($type);
//
//        return array(
//            'number' => sprintf("%s/%s/%s/%s/%s",
//                $type, $enumerator->getNumber(), $enumerator->getMonth(),
//                $enumerator->getYear(), $this->user->getPosition()),
//            'enum' => $enumerator);
//    }
//
//    /**
//     * Zwraca kolejny numer dla Faktury
//     * @param $type
//     * @return array
//     */
//    public function getInvoiceNumber($type)
//    {
//        $enumerator = $this->doctrine
//            ->getRepository(Enumerator::class)->getNumber($type);
//
//        return array(
//            'number' => sprintf("%s/%s/%s/%s",
//                $type, $enumerator->getNumber(), $enumerator->getMonth(),
//                $enumerator->getYear()),
//            'enum' => $enumerator);
//    }
}