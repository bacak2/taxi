<?php

namespace AppBundle\Form\CashRegister;


use AppBundle\DataTransformer\DateToStringTransformer;
use AppBundle\Entity\CashRegister\CashRegisterReport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CashRegisterReportForm extends AbstractType
{
    /**
     * @var DateToStringTransformer
     */
    private $dateTransformer;

    /**
     * CashRegisterReportForm constructor.
     * @param DateToStringTransformer $dateTransformer
     */
    public function __construct(DateToStringTransformer $dateTransformer)
    {
        $this->dateTransformer = $dateTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reportForDate', TextType::class, [
                'attr' => [
                    'class' => 'require-input'
                ]
            ])
            ->add('amount', TextType::class, array(
                'required' => false,
                'attr' => array(
                    'readonly' => 'readonly'
                )
            ))
            ->add('changeAmount', TextType::class, array(
                'required' => false,
                'attr' => [
                    'readonly' => 'readonly'
                ]
            ))
            ->add('prevAmount', TextType::class, array(
                'required' => false,
                'attr' => [
                    'readonly' => 'readonly'
                ]
            ))
            ->add('kpCount', TextType::class, array(
                'required' => false,
                'attr' => [
                    'readonly' => 'readonly'
                ]
            ))
            ->add('kpAmount', TextType::class, array(
                'required' => false,
                'attr' => [
                    'readonly' => 'readonly'
                ]
            ))
            ->add('kwCount', TextType::class, array(
                'required' => false,
                'attr' => [
                    'readonly' => 'readonly'
                ]
            ))
            ->add('kwAmount', TextType::class, array(
                'required' => false,
                'attr' => [
                    'readonly' => 'readonly'
                ]
            ))
            ->add('reportType', ChoiceType::class, [
                'data' => CashRegisterReport::DAILY_REPORT,
                'choices' => [
                    'Raport dzienny' => CashRegisterReport::DAILY_REPORT,
                    'Raport Miesięczny' => CashRegisterReport::MONTHLY_REPORT
                ],
                'attr' => [
                    'class' => 'ui dropdown require-input'
                ]
            ])
            ->add('settlements', HiddenType::class, [
                'mapped' => false
            ])
        ;

        $builder->get('reportForDate')->addModelTransformer($this->dateTransformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults(array(
            'data_class' => CashRegisterReport::class
        ));
    }

    public function getBlockPrefix()
    {
        return 'crr_form';
    }
}