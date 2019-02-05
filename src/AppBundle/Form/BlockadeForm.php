<?php

namespace AppBundle\Form;

use AppBundle\DataTransformer\ComaToDotTransformer;
use AppBundle\DataTransformer\DateToStringTransformer;
use AppBundle\Entity\Blockade;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlockadeForm extends AbstractType
{
    /**
     * @var DateToStringTransformer
     */
    private $dateTransformer;

    /**
     * BlockadeForm constructor.
     * @param DateToStringTransformer $dateTransformer
     */
    public function __construct(DateToStringTransformer $dateTransformer)
    {
        $this->dateTransformer = $dateTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $date = new \DateTime();
        $builder
            ->add('blockadeDate', TextType::class, array(
                'label' => 'Data blokady',
                'attr' => array(
                    'placeholder' => 'Data blokady'
                )
            ))
            ->add('comment', TextareaType::class, array(
                'label' => 'Powód blokady',
                'attr' => array(
                    'rows' => 2
                )
            ))
            ;
        $builder->get('blockadeDate')->addModelTransformer($this->dateTransformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults(array(
            'data_class' => Blockade::class
        ));
    }

    public function getBlockPrefix()
    {
        return 'blockade_form';
    }
}
