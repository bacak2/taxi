<?php

namespace AppBundle\Form\Invoice;

use AppBundle\Entity\Invoice\Invoice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditClientInvoice extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('invoiceNumber')
            ->add('invoiceFormat')
            ->add('amountBrutto')
            ->add('amountNetto')
            ->add('discount');
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
