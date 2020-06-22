<?php

namespace GestionFaenaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AjusteMovimientoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('factorAjuste')
                ->add('tipoMovimiento')
                ->add('conceptoMovimiento')
                ->add('articulo')
                ->add('proceso', 
                        EntityType::class, 
                        [
                            'class' => 'GestionFaenaBundle:ProcesoFaena', 
                            'choices' => [$options['proceso']],
                        ])
                ->add('asignar', SubmitType::class, ['label' => '+']);
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GestionFaenaBundle\Entity\AjusteMovimiento'
        ));
        $resolver->setRequired('proceso');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gestionfaenabundle_ajustemovimiento';
    }


}
