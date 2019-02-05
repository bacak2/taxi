<?php

namespace AppBundle\Form;

use AppBundle\Entity\Terminal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TerminalForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tid', TextType::class, array(
                'label' => 'Numer Terminala',
                'attr' => array(
                    'placeholder' => 'Numer terminala'
                )
            ))
            ->add('taxi360TerminalId', TextType::class, array(
                'label' => 'Taxi360 ID',
                'attr' => array(
                    'placeholder' => 'Taxi360 ID'
                )
            ))
            ->add('status', ChoiceType::class, array(
                'choices' => array(
                    "AKTYWNY" => "ACTIVE",
                    "ZABLOKOWANY" => "BLOCKED"
                ),
                'attr' => array(
                    'class' => 'ui dropdown'
                )
            ))
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults(array(
            'data_class' => Terminal::class
        ));
    }

    public function getBlockPrefix()
    {
        return 'terminal_form';
    }
}
