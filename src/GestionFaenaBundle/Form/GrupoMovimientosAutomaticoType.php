<?php

namespace GestionFaenaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class GrupoMovimientosAutomaticoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $proceso = $options['proceso'];
        $builder->add('orden')
                ->add('nombre')
                ->add('detalle')
                ->add('procesoFaena',
                    EntityType::class, 
                      [
                      'class' => 'GestionFaenaBundle:ProcesoFaena',
                      'choices' => [$proceso],
                      ])
                ->add('guardar', SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GestionFaenaBundle\Entity\GrupoMovimientosAutomatico'
        ));
        $resolver->setRequired('proceso');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gestionfaenabundle_grupomovimientosautomatico';
    }


}
