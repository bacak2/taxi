<?php

namespace AppBundle\Form;

use AppBundle\Entity\ApiTaxi360\Driver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UnassignedDriverForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('driver', EntityType::class, array(
                'class' => Driver::class,
                'placeholder' => 'Wybierz kierowcę',
                'translation_domain' => false,
                'mapped' => false,
                'attr' => array(
                    'class' => 'ui search dropdown'
                )
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getBlockPrefix()
    {
        return 'unassigned_driver_form';
    }
}
