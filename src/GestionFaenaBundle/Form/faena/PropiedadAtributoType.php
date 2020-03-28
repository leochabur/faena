<?php

namespace GestionFaenaBundle\Form\faena;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class PropiedadAtributoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('posicion')
                ->add('mostrar')
                ->add('decimales')
                ->add('controlaStock')
                ->add('atributo', EntityType::class, [
                                                        'class' => 'GestionFaenaBundle:gestionBD\Atributo',
                                                        'query_builder' => function (EntityRepository $er) {
                                                            return $er->createQueryBuilder('u')
                                                                      ->where('u.asignado = :asignado')
                                                                      ->setParameter('asignado', false)
                                                                      ->orderBy('u.nombre', 'ASC');
                                                        }
                                                    ])
                ->add('acumula')
                ->add('promedia');
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GestionFaenaBundle\Entity\faena\PropiedadAtributo'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gestionfaenabundle_faena_propiedadatributo';
    }


}
