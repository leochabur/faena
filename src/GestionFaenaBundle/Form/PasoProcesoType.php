<?php

namespace GestionFaenaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;


class PasoProcesoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $proceso = $options['proceso'];
        $builder->add('orden') 
                ->add('detalle')
                ->add('procesoFaena', 
                      EntityType::class, 
                      [
                      'class' => 'GestionFaenaBundle:ProcesoFaena',
                      'choices' => [$proceso],
                      ])
                ->add('articuloAtributoConcepto', 
                      EntityType::class, 
                      [
                      'class' => 'GestionFaenaBundle:gestionBD\ArticuloAtributoConcepto',
                      'choice_label' => 'vistaEdicion',
                      'required' => false,
                      'query_builder' => function (EntityRepository $er) {
                                                        return $er->createQueryBuilder('a')
                                                                  ->join('a.concepto', 'c')
                                                                  ->where('c.automatico = :automatico')
                                                                  ->andWhere('a.activo = :activo')
                                                                  ->orderBy('c.procesoFaena')
                                                                  ->setParameter('automatico', false)
                                                                  ->setParameter('activo', true);
                                                    }
                      ])
                ->add('grupoMovimiento', 
                      EntityType::class, 
                      [
                      'class' => 'GestionFaenaBundle:GrupoMovimientosAutomatico',
                      'required' => false,
                      'query_builder' => function (EntityRepository $er) use ($proceso){
                                                        return $er->createQueryBuilder('a')
                                                                  ->where('a.procesoFaena = :proceso')
                                                                  ->orderBy('a.orden')
                                                                  ->setParameter('proceso', $proceso);
                                                    }
                      ])
                ->add('guardar', SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GestionFaenaBundle\Entity\PasoProceso'
        ));
        $resolver->setRequired('proceso');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gestionfaenabundle_pasoproceso';
    }


}
