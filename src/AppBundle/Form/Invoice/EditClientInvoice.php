<?php

namespace AppBundle\Form\Invoice;

use AppBundle\Entity\Invoice\Invoice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EditClientInvoice extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('invoiceNumber', TextType::class, [
                'label' => 'Numer faktury'
            ])
            //->add('invoiceFormat')
            ->add('amountBrutto', TextType::class, [
                'label' => 'Kwota bruto'
            ])
            ->add('amountNetto', TextType::class, [
                'label' => 'Kwota netto'
            ])
            ->add('discount', TextType::class, [
                'label' => 'Zniżka'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Invoice::class
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_edit_invoice';
    }
}
