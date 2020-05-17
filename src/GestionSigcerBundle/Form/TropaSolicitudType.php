<?php

namespace GestionSigcerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class TropaSolicitudType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('fechaElaboracion', DateType::class, ['widget' => 'single_text'])
                ->add('fechaFaena', DateType::class, ['widget' => 'single_text'])
                ->add('fechaVto', DateType::class, ['widget' => 'single_text'])
                ->add('lote');
        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);
    }

    public function onPreSetData(FormEvent $event)
    {
        $valor = $event->getData();
        $form = $event->getForm();
        $form->add('grupoSolicitud', 
                    EntityType::class, 
                    ['class' => 'GestionSigcerBundle\Entity\GrupoSolicitud', 
                     'choices' => [$valor->getGrupoSolicitud()]
                    ]);   
        if ($valor->getId())
            $label = 'Modificar Tropa';
        else
            $label = 'Agregar Tropa';


        $form->add('guardar', SubmitType::class, ['label' => $label]);     
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GestionSigcerBundle\Entity\TropaSolicitud'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gestionsigcerbundle_tropasolicitud';
    }


}
