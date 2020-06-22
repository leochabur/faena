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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\NotNull;

class SolicitudType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $grupo = $options['grupo'];

        if ($grupo->getExportacion())
        {
            $builder->add('patenteCamion', TextType::class, ['constraints' => [new NotNull(['message' => "El campo no puede permanecer en blanco!!"])]])
                    ->add('patenteAcoplado')
                    ->add('precintoSenasa', TextType::class, ['data' => '1'])
                    ->add('remitoNumero', TextType::class, ['constraints' => [new NotNull(['message' => "El campo no puede permanecer en blanco!!"])]])
                    ->add('temperatura', TextType::class, ['data' => '-18'])
                    ->add('destinatarioExportacion',
                          EntityType::class,
                          [
                            'class' => 'GestionSigcerBundle:opciones\DestinatarioExportacion',
                            'constraints' => [new NotNull(['message' => "Debe seleccionar una opcion!!"])]
                          ])
                    ->add('numeroContenedor', TextType::class, ['constraints' => [new NotNull(['message' => "El campo no puede permanecer en blanco!!"])]])
                    ->add('nombreBuque', TextType::class, ['constraints' => [new NotNull(['message' => "El campo no puede permanecer en blanco!!"])]])                    
                    ->add('tipoTransporte', ChoiceType::class, [
                                        'choices'  => [
                                                        'Maritimo' => 'MA',
                                                        'Aereo' => 'AE',
                                                        'Terrestre' => 'TE'
                                        ],
                                    ]);
        }
        else
        {
            $builder->add('lugarDestino')
                    ->add('precintoAduana')
                    ->add('precintoSenasa')
                    ->add('observaciones')                    
                    ->add('temperatura')
                    ->add('zona')
                    ->add('precintos')
                    ->add('termoTemperatura')
                    ->add('termoTiempo', TimeType::class, ['widget' => 'single_text',])
                    ->add('cliente')
                    ->add('camion');                    
        }
        $builder->add('guardar', SubmitType::class)
                ->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);
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
        $resolver->setRequired('grupo');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gestionsigcerbundle_solicitud';
    }


}
