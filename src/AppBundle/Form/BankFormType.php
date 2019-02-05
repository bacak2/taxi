<?php

namespace AppBundle\Form;

use AppBundle\Entity\ApiTaxi360\Driver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BankFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateFrom', TextType::class, [
                'label' => 'Data Od',
                'data' => (new \DateTime())->format('Y-m-d')
            ])
            ->add('dateTo', TextType::class, [
                'label' => 'Data Do',
                'data' => (new \DateTime())->format('Y-m-d')
            ])
            ->add('driver', EntityType::class, [
                'class' => Driver::class,
                'placeholder' => 'Kierowca',
                'attr' => [
                    'class' => 'ui search dropdown'
                ]
            ])
            ->add('bezgotowka', CheckboxType::class, [
                'label' => 'Transakcje bezgotówkowe',
                'data' => true
            ])
            ->add('importpko', CheckboxType::class, [
                'label' => 'Karty płatnicze',
                'data' => true
            ])
            ->add('periodic', CheckboxType::class, [
                'label' => 'Przelewy okresowe',
                'data' => true
            ])
            ->add('nonPeriodic', CheckboxType::class, [
                'label' => 'Przelewy nieokresowe',
                'data' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getBlockPrefix()
    {
        return 'bank';
    }
}
