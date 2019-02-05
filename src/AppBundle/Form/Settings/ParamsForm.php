<?php

namespace AppBundle\Form\Settings;

use AppBundle\DataTransformer\ComaToDotTransformer;
use AppBundle\Entity\Form\SettingsFormData;
use AppBundle\Entity\Params\Param;
use AppBundle\Entity\Params\TaxiSettings;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParamsForm extends AbstractType
{
    /**
     * @var ComaToDotTransformer
     */
    private $comaToDotTransformer;

    /**
     * ParamsForm constructor.
     * @param ComaToDotTransformer $comaToDotTransformer
     */
    public function __construct(ComaToDotTransformer $comaToDotTransformer)
    {
        $this->comaToDotTransformer = $comaToDotTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('americanExpress', PercentType::class, array(
                'scale'=> 1
            ))
            ->add('visaMasterCard',PercentType::class, array(
                'scale'=>1
            ))
            ->add('card',PercentType::class, array(
                'scale'=>1
            ))
            ->add('voucher',PercentType::class,array(
                'scale' => 1
            ))
            ->add('eVoucher',PercentType::class, array(
                'scale' => 1
            ))
            ->add('bankTransferCost', MoneyType::class)

            ->add('bankName', TextType::class)
            ->add('bankAccount', TextType::class)
            ->add('bankAccountToFirm', TextType::class)
            ->add('swift', TextType::class)
            ->add('freeTransferBankAccount', TextType::class)
            ->add('vat', PercentType::class, array(
                'scale' => 1
            ))
            ->add('daysToPay', TextType::class)
            ;

        $builder->get('americanExpress')->addModelTransformer($this->comaToDotTransformer);
        $builder->get('visaMasterCard')->addModelTransformer($this->comaToDotTransformer);
        $builder->get('card')->addModelTransformer($this->comaToDotTransformer);
        $builder->get('voucher')->addModelTransformer($this->comaToDotTransformer);
        $builder->get('eVoucher')->addModelTransformer($this->comaToDotTransformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults(array(
            'data_class' => TaxiSettings::class
        ));
    }

    public function getBlockPrefix()
    {
        return 'spf';
    }
}
