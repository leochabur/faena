<?php

namespace GestionFaenaBundle\Form\gestionBD;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AtributoMedibleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('unidadMedida')
                ->add('decimales')
                ->add('acumula')
                ->add('promedia')
                ->add('atributo', AtributoType::class, array(
                                    'data_class' => 'GestionFaenaBundle\Entity\gestionBD\AtributoMedible',
                                ));  
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
      /*  $resolver->setDefaults(array(
            'data_class' => 'GestionFaenaBundle\Entity\gestionBD\AtributoMedible'
        ));*/
        $resolver->setDefaults(array(
            'inherit_data' => true,
        )); 
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gestionfaenabundle_gestionbd_atributomedible';
    }


}
