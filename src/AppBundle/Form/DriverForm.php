<?php

namespace AppBundle\Form;

use AppBundle\DataTransformer\ComaToDotTransformer;
use AppBundle\Entity\ApiTaxi360\Driver;
use AppBundle\Entity\Blockade;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DriverForm extends AbstractType
{
    /**
     * @var ComaToDotTransformer
     */
    private $comaToDot;

    /**
     * DriverForm constructor.
     * @param ComaToDotTransformer $comaToDot
     */
    public function __construct(ComaToDotTransformer $comaToDot)
    {
        $this->comaToDot = $comaToDot;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cabSideNumber', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Numer boczny',
                    'class' => 'require-input'
                )
            ))
            ->add('firstName', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Imię',
                    'class' => 'require-input'
                )
            ))
            ->add('surname', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Nazwisko',
                    'class' => 'require-input'
                )
            ))
            ->add('email', TextType::class, array(
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Email'
                )
            ))
            ->add('mobileNumber', TextType::class, array(
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Telefon'
                )
            ))
            ->add('addressStreet', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Ulica',
                    'class' => 'require-input'
                )
            ))
            ->add('addressPostalCode', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Kod pocztowy(00-000)',
                    'class' => 'require-input'
                )
            ))
            ->add('addressTown', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Miasto',
                    'class' => 'require-input'
                )
            ))
//            ->add('addressCountry', TextType::class, array(
//                'label' => 'Kraj',
//                'attr' => array(
//                    'placeholder' => 'Kraj',
//                    'class' => 'red-border'
//                )
//            ))
            ->add('mailingAddressStreet', TextType::class, array(
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Ulica'
                )
            ))
            ->add('mailingAddressPostalCode', TextType::class, array(
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Kod pocztowy'
                )
            ))
            ->add('mailingAddressTown', TextType::class, array(
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Miasto'
                )
            ))
            ->add('posNumberMid', TextType::class, array(
                'required' => false,
                'attr' => array(
                    'placeholder' => 'POS numer MID'
                )
            ))
            ->add('accountNumber', TextType::class, array(
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Numer konta bankowego'
                )
            ))
            ->add('accountOwner', TextType::class, array(
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Właściciel konta'
                )
            ))
            ->add('terminalTid', TextType::class, array(
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Terminal TID'
                )
            ))
            ->add('status', TextType::class, array(
                'required' => false,
                'attr' => array(
                    'placeholder' => '',
                    'readonly' => 'readonly'
                )
            ))
            ->add('firmName', TextType::class, array(
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Nazwa firmy'
                )
            ))
            ->add('nip', TextType::class, array(
                'required' => false,
                'attr' => array(
                    'placeholder' => 'NIP'
                )
            ))

            ->add('vat', PercentType::class, array(
                'required' => false,
                'attr' => array(
                    'class' => 'ui disabled'
                )
            ))
            ->add('blockade', CollectionType::class, array(
                'entry_type' => BlockadeForm::class,
                'label' => false,
                'allow_delete' => true,
                'allow_add' => true,
                'by_reference' => false
            ))
            ->add('internetPassword', TextType::class, array(
                'required' => false,
                'label' => 'Hasło do Internetu'
            ))
            ->add('comment', TextareaType::class, array(
                'required' => false,
                'label' => 'Notatki'
            ))
            ;
        $builder->get('vat')->addModelTransformer($this->comaToDot);
        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event)
            {
                $form = $event->getForm();
                $data = $event->getData();

                $form
                    ->add('freeMoneyTransfer', ChoiceType::class, array(
                        'label' => 'Darmowe przelewy',
                        'choices' => array(
                            'TAK' => true,
                            'NIE' => false
                        ),
                        'attr' => array(
                            'class' => 'ui dropdown'
                        )
                    ))
                    ->add('isBaggage', ChoiceType::class, array(
                        'label' => 'Przejazdy bagażowe',
                        'choices' => array(
                            'TAK' => true,
                            'NIE' => false
                        ),
                        'attr' => array(
                            'class' => 'ui dropdown'
                        )
                    ))
                    ->add('vatPayer', ChoiceType::class, array(
                        'label' => 'Płatnik VAT',
                        'choices' => array(
                            'TAK' => true,
                            'NIE' => false
                        ),
                        'attr' => array(
                            'class' => 'ui dropdown'
                        )
                    ))
                    ->add('periodicTransfer', ChoiceType::class, array(
                        'label' => 'Otrzymuje przelewy okresowe',
                        'choices' => array(
                            'TAK' => true,
                            'NIE' => false
                        ),
                        'attr' => array(
                            'class' => 'ui dropdown'
                        )
                    ))
                    ->add('invoiceForProvision', ChoiceType::class, array(
                        'label' => 'Wystaw fakturę za prowizję i skup kursów',
                        'choices' => array(
                            'TAK' => true,
                            'NIE' => false
                        ),
                        'attr' => array(
                            'class' => 'ui dropdown'
                        )
                    ))
                ;
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults(array(
            'data_class' => Driver::class
        ));
    }

    public function getBlockPrefix()
    {
        return 'driver_form';
    }
}
