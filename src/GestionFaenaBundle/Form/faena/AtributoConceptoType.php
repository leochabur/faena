<?php

namespace GestionFaenaBundle\Form\faena;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use GestionFaenaBundle\Entity\gestionBD\ArticuloProcesoFaena;
use GestionFaenaBundle\Repository\gestionBD\ArticuloProcesoFaenaRepository;
class AtributoConceptoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('concepto')
                ->add('atributos')
                ->add('articulo', EntityType::class, 
                              [
                              'class' =>  ArticuloProcesoFaena::class,
                              'query_builder' => function (ArticuloProcesoFaenaRepository $er) {
                                                                                        return $er->createQueryBuilder('u')
                                                                                            ->where('u.activo = :activo')
                                                                                            ->setParameter('activo', true);
                                                                                    },
                                ])
                ->add('guardar', SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GestionFaenaBundle\Entity\faena\AtributoConcepto'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gestionfaenabundle_faena_atributoconcepto';
    }


}
