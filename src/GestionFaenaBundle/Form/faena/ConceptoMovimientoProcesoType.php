<?php

namespace GestionFaenaBundle\Form\faena;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class ConceptoMovimientoProcesoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('tipoMovimiento')
                ->addEventListener(FormEvents::PRE_SET_DATA, 
                                    function (FormEvent $event) {
                                                                $concepto = $event->getData();
                                                                $form = $event->getForm();
                                                                if ($concepto && $concepto->getId()) {
                                                                    $form->add('procesoFaena', 
                                                                                EntityType::class, 
                                                                                [
                                                                                    'class' => 'GestionFaenaBundle\Entity\ProcesoFaena',
                                                                                    'choices' => [$concepto->getProcesoFaena()]
                                                                                ]
                                                                            )
                                                                            ->add('concepto', 
                                                                                EntityType::class, 
                                                                                [
                                                                                    'class' => 'GestionFaenaBundle\Entity\faena\ConceptoMovimiento',
                                                                                    'choices' => [$concepto->getConcepto()]
                                                                                ]
                                                                            );
                                                                } else {
                                                                        $form->add('procesoFaena')
                                                                             ->add('concepto');
                                                                }
                                                            }
                 )
                 ->add('guardar', SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GestionFaenaBundle\Entity\faena\ConceptoMovimientoProceso'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gestionfaenabundle_faena_conceptomovimientoproceso';
    }


}
