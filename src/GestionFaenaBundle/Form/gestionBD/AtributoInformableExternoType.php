<?php

namespace GestionFaenaBundle\Form\gestionBD;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use GestionFaenaBundle\Entity\gestionBD\Granja; 
use GestionFaenaBundle\Entity\gestionBD\Transportista;
use GestionFaenaBundle\Entity\gestionBD\Cargador;
use GestionFaenaBundle\Entity\gestionBD\Sucursal;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
class AtributoInformableExternoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('atributoInformable', AtributoInformableType::class, array(
                                    'data_class' => 'GestionFaenaBundle\Entity\gestionBD\AtributoInformableExterno',
                                ))
                ->add('claseExterna', 
                     ChoiceType::class, 
                     ['choices' => ['Granja'=> Granja::class , 
                                    'Transportista' => Transportista::class, 
                                    'Cargador' => Cargador::class,
                                    'Sucursal' => Sucursal::class]])
                ->add('guardar', SubmitType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GestionFaenaBundle\Entity\gestionBD\AtributoInformableExterno'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gestionfaenabundle_gestionbd_atributoinformableexterno';
    }


}
