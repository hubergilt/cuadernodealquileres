<?php

namespace App\Form;

use App\Entity\Deposito;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepositoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('monto')
            ->add('anio')
            ->add('mes')
            ->add('observacion')
            ->add('fechaDeposito')
            ->add('arrendatario')
            ->add('ambiente')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Deposito::class,
        ]);
    }
}
