<?php

namespace GestionSigcerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

class SolicitudType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('lugarDestino')
                ->add('precintoAduana')
                ->add('precintoSenasa')
                ->add('observaciones')
                ->add('remitoNumero')
                ->add('temperatura')
                ->add('zona')
                ->add('precintos')
                ->add('termoTemperatura')
                ->add('termoTiempo', TimeType::class, ['widget' => 'single_text',])
                ->add('cliente')
                ->add('camion')
                ->add('guardar', SubmitType::class);
        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);
    }

    public function onPreSetData(FormEvent $event)
    {
        $valor = $event->getData();
        $form = $event->getForm();
        $form->add('grupo', 
                    EntityType::class, 
                    ['class' => 'GestionSigcerBundle\Entity\GrupoSolicitud', 
                     'choices' => [$valor->getGrupo()]
                    ]);        
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GestionSigcerBundle\Entity\Solicitud'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gestionsigcerbundle_solicitud';
    }


}
