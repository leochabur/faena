<?php

namespace GestionSigcerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class DetalleSolicitudType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('pesoNeto')
                ->add('pesoBruto')
                ->add('cantidad')
                ->add('certificadoOrigen')
                ->add('articulo')                
                ->add('envasePrimario')
                ->add('envaseSecundario')
                ->add('guardar', SubmitType::class);
        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);
    }

    public function onPreSetData(FormEvent $event)
    {
        $valor = $event->getData();
        $form = $event->getForm();
        $form->add('solicitud', 
                    EntityType::class, 
                    ['class' => 'GestionSigcerBundle\Entity\Solicitud', 
                     'choices' => [$valor->getSolicitud()]
                    ])
             ->add('tropa', 
                    EntityType::class, 
                    ['class' => 'GestionSigcerBundle\Entity\TropaSolicitud', 
                     'choices' => $valor->getSolicitud()->getGrupo()->getTropas()
                    ]);      
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GestionSigcerBundle\Entity\DetalleSolicitud'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gestionsigcerbundle_detallesolicitud';
    }


}
