<?php

namespace AppBundle\Form;

use AppBundle\DataTransformer\DateToStringTransformer;
use AppBundle\Entity\ApiTaxi360\Driver;
use AppBundle\Entity\ApiTaxi360\Transaction;
use AppBundle\Entity\Blockade;
use AppBundle\Repository\DriverRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;

class BlockadeAddForm extends AbstractType
{
    /**
     * @var DateToStringTransformer
     */
    private $dateTransformer;

    /**
     * BlockadeAddForm constructor.
     * @param DateTimeToStringTransformer $dateTransformer
     */
    public function __construct(DateToStringTransformer $dateTransformer)
    {
        $this->dateTransformer = $dateTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('driver', EntityType::class, array(
                'class' => Driver::class,
                'placeholder' => '',
                'attr' => array(
                    'class' => 'ui search dropdown driver'
                ),
                'query_builder' => function(EntityRepository $em){
                    /** Lista aktywnych kierowcow,  którzy nie mają blokady */
                    return $em->createQueryBuilder('d')
                        ->where('d.blocked = :blocked')
                        ->andWhere("d.status = 'ACTIVE'")
                        ->setParameter('blocked', false);
                }
            ))
            ->add('blockadeType', ChoiceType::class, [
                'choices' => [
                    'Wszystkie' => Transaction::WSZYSTKIE,
                    'ImportPKO' => Transaction::GOTOWKA,
                    'Bezgotowka' => Transaction::BEZGOTOWKA
                ],
                'attr' => [
                    'class' => 'ui dropdown transaction-type'
                ]
            ])
            ->add('blockadeDate', TextType::class, array(
                'data' => (new \DateTime())->format('Y-m-d')
            ))
            ->add('comment', TextareaType::class, array(
                'label' => 'Komentarz',
                'required' => false,
                'attr' => array(
                    'rows' => 3
                )
            ));
        $builder->get('blockadeDate')->addViewTransformer($this->dateTransformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults(array(
            'data_class' => Blockade::class
        ));
    }

    public function getBlockPrefix()
    {
        return 'blockade_add_form';
    }
}
