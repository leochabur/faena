<?php

namespace GestionFaenaBundle\Form\faena;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class MovimientoStockType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('cantidad1')
                ->add('cantidad2')
                ->add('unidadMedida1')
                ->add('unidadMedida2')
                ->add('procesoFnDay', EntityType::class, ['class' => 'GestionFaenaBundle\Entity\faena\ProcesoFaenaDiaria','choices' => [$options['proceso']]])
                ->add('articulo')
                ->add('concepto');
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'inherit_data' => true,
        )); 
        $resolver->setRequired('proceso'); 
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gestionfaenabundle_faena_movimientostock';
    }


}
