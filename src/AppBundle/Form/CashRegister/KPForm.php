<?php

namespace AppBundle\Form\CashRegister;

use AppBundle\DataTransformer\ComaToDotTransformer;
use AppBundle\DataTransformer\DateToStringTransformer;
use AppBundle\Entity\ApiTaxi360\Client;
use AppBundle\Entity\ApiTaxi360\Driver;
use AppBundle\Entity\CashRegister\CashRegister;
use AppBundle\Entity\Enumerator;
use AppBundle\Entity\Params\Param;
use AppBundle\Entity\Dictionary\DictionaryParam;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class KPForm extends AbstractType
{
    /**
     * @var ComaToDotTransformer
     */
    private $comaToDotTransformer;

    /**
     * @var DateToStringTransformer
     */
    private $dateTransformer;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * KWForm constructor.
     * @param ComaToDotTransformer $comaToDotTransformer
     */
    public function __construct(
        DateToStringTransformer $dateToStringTransformer,
        ComaToDotTransformer $comaToDotTransformer, EntityManagerInterface $em)
    {
        $this->comaToDotTransformer = $comaToDotTransformer;
        $this->dateTransformer = $dateToStringTransformer;
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('transactionDate', TextType::class, array(
                'label' => 'Data',
                'attr' => array(
                    'class' => 'ui disabled input'
                )
            ))
            ->add('recipient', ChoiceType::class, array(
                'label' => 'Kierowca',
                'placeholder' => 'Wybierz kierowcę',
                'choices' => $this->getSelectValues(),
                'mapped' => false,
                'attr' => array(
                    'class' => 'ui search scrolling dropdown'
                )
            ))
            ->add('title', TextType::class, array(
                'label' => 'Tytułem',
                'required' => false
            ))
            ->add('amount', MoneyType::class, array(
                'label' => 'Kwota',
                'mapped' => false,
            ))
            ->add('param', EntityType::class, array(
                'class' => DictionaryParam::class,
                'placeholder' => 'Rodzaj',
                'label' => 'Rodzaj/Towar',
                'mapped' => false,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('dp')
                        ->where("dp.category = :categoryId")
                        ->setParameter(':categoryId', 5)
                        ;
                },
                'attr' => array(
                    'class' => 'ui dropdown'
                )
            ))
            ;
        $builder->get('amount')->addModelTransformer($this->comaToDotTransformer);
        $builder->get('transactionDate')->addModelTransformer($this->dateTransformer);

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function(FormEvent $event)use($options){
                $form = $event->getForm();
                /**
                 * @var $data CashRegister
                 */
                $data = $event->getData();

                //DRIVER OR FIRM
                $recipient = $form->get('recipient')->getData();
                if($recipient instanceof Driver)
                {
                    $data->setDriver($recipient);
                }elseif($recipient instanceof Client)
                {
                    $data->setClient($recipient);
                }
                $data->setTransactionType(CashRegister::TYPE_KP);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults(array(
            'data_class' => CashRegister::class
        ));
    }

    public function getBlockPrefix()
    {
        return 'kpform';
    }

    protected function getSelectValues()
    {
        $driverRepo = $this->em->getRepository(Driver::class);
        $result = array();

        //TODO: Sprawdzac czy kierowca nie zostal usuniety
        $drivers = $driverRepo->findAll();
        /**
         * @var Driver $driver
         */
        foreach ($drivers as $driver) {
            $id = 'Kierowca: '.$driver->getLicenseNumber() .' - '. $driver->getFirstName(). ' ' . $driver->getSurname()  ;
            $result[$id] = $driver;
        }

        return $result;
    }
}
