<?php

namespace AppBundle\Form;

use AppBundle\DataTransformer\ComaToDotTransformer;
use AppBundle\DataTransformer\DateToStringTransformer;
use AppBundle\Entity\ApiTaxi360\Client;
use AppBundle\Entity\ApiTaxi360\Driver;
use AppBundle\Entity\ApiTaxi360\Transaction;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddVoucherForm extends AbstractType
{
    /**
     * @var ComaToDotTransformer
     */
    private $dotTransformer;

    /**
     * @var DateToStringTransformer
     */
    private $dateTransformer;

    /**
     * AddVoucherForm constructor.
     * @param ComaToDotTransformer $dotTransformer
     */
    public function __construct(ComaToDotTransformer $dotTransformer, DateToStringTransformer $dateTransformer)
    {
        $this->dotTransformer = $dotTransformer;
        $this->dateTransformer = $dateTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('voucherNumber', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Voucher'
                )
            ))
            ->add('voucherDescription', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Kart/kod'
                )
            ))
            ->add('clientId', EntityType::class, array(
                'class' => Client::class,
                'placeholder' => 'Wybierz firmę',
                'attr' => array(
                    'class' => 'ui search dropdown'
                )
            ))
            ->add('transactionDate', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Data transakcji'
                )
            ))
            ->add('totalAmount', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Kwota'
                )
            ))
            ->add('percentage', PercentType::class, array(
                'scale' => 1,
                'mapped' => false,
                'attr' => array(
                    'placeholder' => 'Prowizja'
                )
            ))
            ->add('driverId', EntityType::class, array(
                'class' => Driver::class,
                'placeholder' => 'Kierowca',
                'attr' => array(
                    'class' => 'ui search dropdown'
                )
            ))
            ;
        $builder->get('totalAmount')->addModelTransformer($this->dotTransformer);
        $builder->get('percentage')->addModelTransformer($this->dotTransformer);
        $builder->get('transactionDate')->addModelTransformer($this->dateTransformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults(array(
            'data_class' => Transaction::class,
            'validation_groups' => 'Voucher'
        ));
    }

    public function getBlockPrefix()
    {
        return 'add_voucher_form';
    }
}
