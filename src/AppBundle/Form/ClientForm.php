<?php

namespace AppBundle\Form;

use AppBundle\DataTransformer\ComaToDotTransformer;
use AppBundle\DataTransformer\DateToStringTransformer;
use AppBundle\Entity\ApiTaxi360\Card;
use AppBundle\Entity\ApiTaxi360\Client;
use AppBundle\Entity\Firm;
use AppBundle\Form\AddressFormType;
use AppBundle\Form\Card\CardForm;
use AppBundle\Form\FirmAgreementForm;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientForm extends AbstractType
{
    /**
     * @var DateToStringTransformer
     */
    private $dateTransformer;

    private $dotComaTransformer;

    /**
     * ClientForm constructor.
     * @param DateToStringTransformer $dateTransformer
     */
    public function __construct(DateToStringTransformer $dateTransformer, ComaToDotTransformer $dotComa)
    {
        $this->dateTransformer = $dateTransformer;
        $this->dotComaTransformer = $dotComa;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Nazwa firmy',
                'attr' => array(
                    'placeholder' => 'Nazwa firmy',
                    'class' => 'red-border'
                )
            ))
            ->add('nip', TextType::class, array(
                'label' => 'NIP',
                'attr' => array(
                    'placeholder' => 'NIP',
                    'class' => 'red-border'
                )
            ))
            ->add('email', EmailType::class,array(
                'label' => 'Email',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Email'
                )
            ))
            ->add('phoneNumber', TextType::class,array(
                'label' => 'Telefon',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Telefon'
                )
            ))
            ->add('status', TextType::class,array(
                'label' => 'Status',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Status'
                )
            ))
            ->add('addressStreet', TextType::class,array(
                'label' => 'Ulica i numer',
                'attr' => array(
                    'placeholder' => 'Ulica i numer',
                    'class' => 'red-border'
                )
            ))
            ->add('addressPostalCode', TextType::class,array(
                'label' => 'Kod pocztowy',
                'attr' => array(
                    'placeholder' => 'Kod pocztowy',
                    'class' => 'red-border'
                )
            ))
            ->add('addressTown', TextType::class,array(
                'label' => 'Miasto',
                'attr' => array(
                    'placeholder' => 'Miasto',
                    'class' => 'red-border'
                )
            ))
            ->add('addressCountry', TextType::class,array(
                'label' => 'Kraj',
                'attr' => array(
                    'placeholder' => 'Kraj',
                    'class' => 'red-border'
                )
            ))
            ->add('mailingAddressStreet', TextType::class,array(
                'label' => 'Ulica i numer',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Ulica i numer'
                )
            ))
            ->add('mailingAddressPostalCode', TextType::class,array(
                'label' => 'Kod pocztowy',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Kod pocztowy'
                )
            ))
            ->add('mailingAddressTown', TextType::class,array(
                'label' => 'Miasto',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Miasto'
                )
            ))
            ->add('mailingAddressCountry', TextType::class,array(
                'label' => 'Kraj',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Kraj'
                )
            ))
            ->add('agreementNumber', TextType::class,array(
                'label' => 'Numer umowy',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Numer umowy'
                )
            ))
            ->add('agreementUntil', TextType::class,array(
                'label' => 'Data końca umowy',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Data końca umowy'
                )
            ))
            ->add('paymentDays', TextType::class,array(
                'label' => 'Ilość dni do zapłaty',
                'attr' => array(
                    'placeholder' => 'Ilość dni do zapłaty',
                    'class' => 'red-border'
                )
            ))
            ->add('discount', PercentType::class,array(
                'label' => 'Zniżka dla firmy',
                'required' => false,
                'scale' => 1,
                'attr' => array(
                    'placeholder' => 'Zniżka dla firmy'
                )
            ))
            ->add('cardProvision', PercentType::class,array(
                'label' => 'Karta własna',
                'required' => false,
                'scale' => 1,
                'attr' => array(
                    'placeholder' => 'Karta własna'
                )
            ))
            ->add('eVoucherProvision', PercentType::class,array(
                'label' => 'eVoucher\'a',
                'required' => false,
                'scale' => 1,
                'attr' => array(
                    'placeholder' => 'eVoucher'
                )
            ))
            ->add('voucherProvision', PercentType::class,array(
                'label' => 'Voucher',
                'required' => false,
                'scale' => 1,
                'attr' => array(
                    'placeholder' => 'Voucher'
                )
            ))
            ->add('invoiceName', TextType::class,array(
                'label' => 'Nazwa na fakturze',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Nazwa na fakturze'
                )
            ))
            ->add('invoiceSign', TextType::class,array(
                'label' => 'Podpis na fakturze(odbiorcy)',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Podpis na fakturze'
                )
            ))
            ->add('envelopeSign', TextType::class,array(
                'label' => 'Podpis na kopercie',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Podpis na kopercie'
                )
            ))
            ->add('invoiceEmail', TextType::class,array(
                'label' => 'Email dla faktury',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Email dla faktury'
                )
            ))
            ->add('comment', TextareaType::class,array(
                'label' => 'Notatki',
                'required' => false,
                'attr' => array(

                )
            ))
            ->add('ownProvision', CheckboxType::class, array(
                'label' => 'Firma ma ustalone własne prowizje',
                'required' => false,
            ))
            ->add('skipInvoice', CheckboxType::class, array(
                'label' => 'Pomiń przy fakturowaniu za przejazdy bezgotówkowe',
                'required' => false,
            ))
            ->add('cardMonthlyLimit', TextType::class, [])
            ->add('cardDailyLimit', TextType::class, [])
            ->add('cardWorkingDays', TextType::class, [])
        ;
        $builder->get('agreementUntil')->addModelTransformer($this->dateTransformer);
        $builder->get('discount')->addModelTransformer($this->dotComaTransformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults(array(
            'data_class' => Client::class
        ));
    }

    public function getBlockPrefix()
    {
        return 'client_form';
    }
}
