<?php

namespace AppBundle\Form\Card;

use AppBundle\DataTransformer\ComaToDotTransformer;
use AppBundle\Entity\ApiTaxi360\Card;
use AppBundle\Entity\ApiTaxi360\Client;
use AppBundle\Entity\ApiTaxi360\Passenger;
use AppBundle\Entity\Settings\Param;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CardForm extends AbstractType
{
    /**
     * @var ComaToDotTransformer
     */
    private $dotTransformer;

    /**
     * CardForm constructor.
     * @param ComaToDotTransformer $dotTransformer
     */
    public function __construct(ComaToDotTransformer $dotTransformer)
    {
        $this->dotTransformer = $dotTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pan', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'PAN - numer kary'
                )
            ))
            ->add('taxiCardId', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Numer karty w systemie Taxi360'
                )
            ))
            ->add('client', EntityType::class, array(
                'class' => Client::class,
                'placeholder' => 'Firma',
                'attr' => array(
                    'class' => 'ui search dropdown'
                )
            ))
            ->add('taxiPassengerId', TextType::class, array(
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Pasażer z systemu Taxi360'
                )
            ))
            ->add('cardType', TextType::class, array(

            ))
            ->add('status', TextType::class, array(

            ))
            ->add('dailyLimit', TextType::class, array(

            ))
            ->add('monthlyLimit', TextType::class, array(

            ))
            ->add('dailyUsage', TextType::class, array(

            ))
            ->add('workingDays', TextType::class, array(

            ))
            ->add('validUntil', TextType::class, array(
                'attr' => array(
                    'class' => ''
                )
            ))
            ->add('availableAmount', TextType::class, array(

            ))
            ->add('comment', TextareaType::class, array(
                'required' => false,
                'attr' => array(
                    'rows' => 2
                )
            ))
            ->add('department', TextType::class, array(
                'required' => false
            ))
            ->add('discount', PercentType::class, array(
                'scale'=>1
            ))
            ->add('isActive', CheckboxType::class, array(
                'required' => false
            ))
        ;
        $builder->get('dailyLimit')->addModelTransformer($this->dotTransformer);
        $builder->get('availableAmount')->addModelTransformer($this->dotTransformer);
        $builder->get('monthlyLimit')->addModelTransformer($this->dotTransformer);

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event)
            {
                $data = $event->getData();
                $isActive = (!in_array($data->getStatus(), array('BLOCKED', 'RETIRED', 'SPENT'))) ? true : false;

                $data->setIsActive($isActive);
                $data->setDiscount((empty($data->getDiscount)) ? 0 : $data->getDiscount());
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults(array(
            'data_class' => Card::class
        ));
    }

    public function getBlockPrefix()
    {
        return 'form_card';
    }
}
