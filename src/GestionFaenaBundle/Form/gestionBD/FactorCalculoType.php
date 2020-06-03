<?php

namespace GestionFaenaBundle\Form\gestionBD;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class FactorCalculoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('articulo',
                     EntityType::class,
                     [ 'class' => 'GestionFaenaBundle\Entity\gestionBD\Articulo',
                       'required' => false,])
                ->add('atributo',
                         EntityType::class,
                         [ 'class' => 'GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto',
                           'required' => false,])
                ->add('soloIngreso', CheckboxType::class, ['label' => false])
                ->add('tipoCalculo', 
                      ChoiceType::class, [
                        'choices'  => [
                            'Acumulado' => 'S',
                            'Promedio' => 'P',
                            'Unitario' => 'U',
                        ],
                        'required'=> false
                ]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GestionFaenaBundle\Entity\gestionBD\FactorCalculo'
        ))
        ->setRequired('articulo');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gestionfaenabundle_gestionbd_factorcalculo';
    }


}
