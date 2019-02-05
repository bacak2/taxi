<?php

namespace AppBundle\Form\CashRegister;

use AppBundle\DataTransformer\ComaToDotTransformer;
use AppBundle\Entity\CashRegister\CashRegisterDetail;
use AppBundle\Entity\Params\Param;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KPDetail extends AbstractType
{
    /**
     * @var ComaToDotTransformer $dotTransformer
     */
    private $dotTransformer;

    /**
     * KPDetail constructor.
     * @param ComaToDotTransformer $dotTransformer
     */
    public function __construct(ComaToDotTransformer $dotTransformer)
    {
        $this->dotTransformer = $dotTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('param', EntityType::class, array(
                'class' => Param::class,
                'placeholder' => false,
                'attr' => array(
                    'class' => 'ui disabled dropdown',
                    'placeholder' => false
                )
            ))
            ->add('netto', TextType::class, array(
                'attr' => array(
                    'style' => 'border:none; text-align: right; padding: 1px;',
                    'readonly' => 'readonly',
                    'class' => 'td-netto'
                )
            ))
            ->add('vat', PercentType::class, array(
                'scale' => 1,
                'attr' => array(
                    'style' => 'width: 70px; border: none; text-align: right',
                    'readonly' => 'readonly'
                )
            ))
            ->add('brutto', TextType::class, array(
                'attr' => array(
                    'style' => 'border: none; text-align: right; padding: 1px;',
                    'readonly' => 'readonly',
                    'class' => 'td-brutto'
                )
            ))
            ->add('quantity', TextType::class, array(
                'attr' => array(
                    'style' => 'border:none;',
                    'readonly' => 'readonly'
                )
            ));

        $builder->get('netto')->addModelTransformer($this->dotTransformer);
        $builder->get('brutto')->addModelTransformer($this->dotTransformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults(array(
            'data_class' => CashRegisterDetail::class
        ));
    }

    public function getBlockPrefix()
    {
        return 'kwdetail';
    }
}
