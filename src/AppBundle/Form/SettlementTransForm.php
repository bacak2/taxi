<?php

namespace AppBundle\Form;

use AppBundle\Entity\ApiTaxi360\Driver;
use AppBundle\Entity\CashRegister\CashRegister;
use AppBundle\Entity\Params\Param;
use AppBundle\Entity\Dictionary\DictionaryParam;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettlementTransForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('driver', EntityType::class, array(
                'class' => Driver::class,
                'placeholder' => 'Wybierz kierowcę',
                'translation_domain' => false,
                'attr' => array(
                    'class' => 'ui search dropdown require-input'
                )
            ))
            ->add('ownCards', CheckboxType::class, array(
                'label' => 'Karty własne',
                'data' => true,
                'required' => false,
                'mapped' => false
            ))
            ->add('bankTransaction', CheckboxType::class, array(
                'label' => 'Transakcje bankowe',
                'data' => true,
                'required' => false,
                'mapped' => false
            ))
            ->add('settlementOrder', ChoiceType::class, array(
                'expanded' => true,
                'multiple' => false,
                'mapped' => false,
                'data' => 'ASC',
                'choices'=>array(
                    'Od najwcześniejszej' => 'ASC',
                    'Od najpóźniejszej' => 'DESC'
                )
            ))
            ->add('deduction', TextType::class, array(
                'mapped' => false,
                'attr' => array(
                    'readonly' => 'readonly'
                )
            ))
            ->add('amount', TextType::class, array(
                'mapped' => false,
                'attr' => array(
                    'readonly' => 'readonly'
                )
            ))
            ->add('toPay', TextType::class, array(
                'mapped' => false,
                'attr' => array(
                    'readonly' => 'readonly',
                    'style' => 'border-color: green; font-weight: bolder; text-align:center; color:green;'
                )
            ))
            ->add('title', TextType::class, array(
                'attr' => array(
                    'class' => 'require-input'
                )
            ))
            ->add('transactions', HiddenType::class, array(
                'mapped' => false,
            ))
            ->add('param', EntityType::class, array(
                'mapped' => false,
                'class' => DictionaryParam::class,
                'placeholder' => 'Typ transakcji',
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('dp')
                        ->where("dp.category = :categoryId")
                        ->setParameter(':categoryId', 7)
                        ;
                },
                'attr' => array(
                    'class' => 'ui dropdown require-input'
                )
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults(array(
            'data_class' => CashRegister::class
        ));
    }

    public function getBlockPrefix()
    {
        return 'settlement_trans_form';
    }
}
