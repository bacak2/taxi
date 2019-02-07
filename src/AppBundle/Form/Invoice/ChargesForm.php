<?php

namespace AppBundle\Form\Invoice;

use AppBundle\DataTransformer\ComaToDotTransformer;
use AppBundle\Entity\ApiTaxi360\Driver;
use AppBundle\Entity\Dictionary\ChargeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChargesForm extends AbstractType
{
    /**
     * @var ComaToDotTransformer
     */
    private $dotTransformer;

    /**
     * ChargesForm constructor.
     * @param ComaToDotTransformer $dotTransformer
     */
    public function __construct(ComaToDotTransformer $dotTransformer)
    {
        $this->dotTransformer = $dotTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('driver', EntityType::class, [
                'label' => 'Wyszukaj kierowcę',
                'class' => Driver::class,
                'attr' => [
                    'class' => 'ui search dropdown'
                ]
            ])
            ->add('addInvoice', ButtonType::class, [
                'label' => 'Dodaj Fakturę',
                'attr' => [
                    'class' => 'ui right floated button',
                ]
            ])
            
            /*->add('chargeType', EntityType::class, [
                'label' => 'Nalicz opłatę',
                'class' => ChargeType::class,
                'attr' => [
                    'class' => 'ui dropdown'
                ]
            ] )*/
            ->add('vat', PercentType::class, [
                'label' => 'VAT(%)',
                'scale' => 1
            ])
            ->add('amount', TextType::class, [
                'label' => 'Kwota'
            ])
            ->add('btnCalculate', ButtonType::class, [
                'label' => 'Nalicz',
                'attr' => [
                    'class' => 'ui right floated button'
                ]
            ])
            
            
            ->add('createPrintKp', CheckboxType::class, [
                'label' => 'Wystaw i drukuj KP'
            ])
            ->add('createDate', TextType::class, [
                'label' => 'Data wystawienia',
                'attr' => [
                    'class' => 'datepicker-full-date',
                    'value' => (new \DateTime())->format('Y-m-d')
                ]
            ])
            ->add('paymentDate', TextType::class, [
                'label' => 'Data Zapłaty',
                'attr' => [
                    'class' => 'datepicker-full-date',
                    'value' => (new \DateTime())->format('Y-m-d')
                ]
            ])
 

            ->add('btnCreatePrintInvoice', SubmitType::class, [
                'label' => 'Wystaw i drukuj fakturę',
                'attr' => [
                    'class' => 'ui green right floated button'
                ]
            ])
            ;
        $builder->get('amount')->addModelTransformer($this->dotTransformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getBlockPrefix()
    {
        return 'charges_form';
    }
}
