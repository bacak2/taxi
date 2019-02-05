<?php

namespace AppBundle\Form\Settings;

use AppBundle\Entity\Params\Param;
use AppBundle\Entity\Params\ParamCategory;
use Composer\Repository\RepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParamForm extends AbstractType
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * ParamForm constructor.
     * @param ServiceEntityRepositoryInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', EntityType::class, array(
                'class' => ParamCategory::class,
                'attr' => array(
                    'class' => 'ui search dropdown'
                )
            ))
            ->add('name', TextType::class, array(
                'label' => 'Wartość'
            ))
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults(array(
            'data_class' => Param::class
        ));
    }

    public function getBlockPrefix()
    {
        return 'form_dictionary';
    }
}
