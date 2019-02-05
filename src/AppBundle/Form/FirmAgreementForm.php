<?php

namespace AppBundle\Form;

use AppBundle\DataTransformer\ComaToDotTransformer;
use AppBundle\Entity\Agreement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FirmAgreementForm extends AbstractType
{
    /**
     * @var ComaToDotTransformer
     */
    private $comaTransformer;

    /**
     * FirmAgreementForm constructor.
     * @param ComaToDotTransformer $comaTransformer
     */
    public function __construct(ComaToDotTransformer $comaTransformer)
    {
        $this->comaTransformer = $comaTransformer;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number', TextType::class, array(
                'label' => 'Numer umowy'
            ))
            ->add('endDate', TextType::class, array(
                'label' => 'Data końca umowy',
                'attr' => array(
                    'class' => 'datepicker'
                )
            ))
            ->add('discount', PercentType::class, array(
                'label' => 'Rabat dla firmy',
                'scale' => 1
            ))
            ->add('paymentDays', NumberType::class, array(
                'label' => 'Ilość dni do zapłaty'
            ))
            ->add('cardProvision', PercentType::class, array(
                'label' => 'Karty 919',
                'scale' => 1
            ))
            ->add('eVoucherProvision', PercentType::class, array(
                'label' => 'eVoucher',
                'scale' => 1
            ))
            ->add('voucherProvision', PercentType::class, array(
                'label' => 'Voucher 919',
                'scale' => 1
            ))
            ->add('deleteBtn', ButtonType::class, array(
                'label' => 'Usun',
                'attr' => array(
                    'class' => 'ui red button btnDeleteAgreement'
                )
            ))
        ;
        $builder->get('discount')->addModelTransformer($this->comaTransformer);
        $builder->get('cardProvision')->addModelTransformer($this->comaTransformer);
        $builder->get('eVoucherProvision')->addModelTransformer($this->comaTransformer);
        $builder->get('voucherProvision')->addModelTransformer($this->comaTransformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults(array(
            'data_class' => Agreement::class
        ));
    }

    public function getBlockPrefix()
    {
        return 'agreement';
    }
}
