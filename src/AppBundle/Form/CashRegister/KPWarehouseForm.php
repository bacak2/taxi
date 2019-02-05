<?php

namespace AppBundle\Form\CashRegister;

use AppBundle\DataTransformer\ComaToDotTransformer;
use AppBundle\Entity\ApiTaxi360\Driver;
use AppBundle\Entity\CashRegister\CashRegister;
use AppBundle\Entity\Params\Param;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KPWarehouseForm extends AbstractType
{
    /**
     * @var ComaToDotTransformer $dotTransformer
     */
    private $dotTransformer;

    /**
     * KPWarehouseForm constructor.
     * @param ComaToDotTransformer $dotTransformer
     */
    public function __construct(ComaToDotTransformer $dotTransformer)
    {
        $this->dotTransformer = $dotTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('driver', EntityType::class, array(
                'class' => Driver::class,
                'placeholder' => 'Wybierz kierowcę',
                'attr' => array(
                    'class' => 'ui search dropdown'
                )
            ))
            ->add('kp_towar', EntityType::class, array(
                'class' => Param::class,
                'placeholder' => '',
                'required' => false,
                'mapped' => false,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('p')
                        ->join('p.category', 'c')
                        ->where("c.slug = :category")
                        ->setParameter('category', CashRegister::SLUG_KP_TOWAR);
                },
                'attr' => array(
                    'class' => 'ui search dropdown'
                )
            ))
            ->add('kp_type', EntityType::class, array(
                'class' => Param::class,
                'placeholder' => '',
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('p')
                        ->join('p.category', 'c')
                        ->where("c.slug = :category")
                        ->setParameter('category', CashRegister::SLUG_KP);
                },
                'attr' => array(
                    'class' => 'ui search dropdown'
                ),
                'mapped' => false
            ))
            ->add('amount', TextType::class, array(
                'required' => false,
                'mapped' => false,
                'attr' => array(
                    'style' => 'text-align: center; border: 1px solid green; color: green; font-weight: bolder;',
                    'readonly' => 'readonly'
                )
            ))
            ->add('cashRegisterDetails', CollectionType::class, array(
                'entry_type' => KPDetail::class,
                'label' => false,
                'required' => false,
                'by_reference' => false,
                'allow_delete' => true,
                'allow_add' => true
            ))
            ->add('submit', SubmitType::class, [
                'label' => 'Zapisz i drukuj potwierdzenie',
                'attr' => [
                    'class' => 'ui green right floated button'
                ]
            ])
            ;
        $builder->get('amount')->addModelTransformer($this->dotTransformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults(array(
            'data_class' => CashRegister::class
        ));
    }

    public function getBlockPrefix()
    {
        return 'kww_form';
    }
}
