<?php

namespace GestionSigcerBundle\Form\opciones;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CamionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('cantPrecintos')->add('patente')->add('senasa')->add('guardar', SubmitType::class)->add('tipo', ChoiceType::class, [
                                                                    'choices'  => [
                                                                        'TERRESTRE' => 'TE',
                                                                        'MARITIMO' => 'MA',
                                                                        'AEREO' => 'AE',
                                                                    ],
                                                                ])->add('zona');
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GestionSigcerBundle\Entity\opciones\Camion'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gestionsigcerbundle_opciones_camion';
    }


}
