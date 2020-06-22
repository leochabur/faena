<?php

namespace GestionSigcerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class GrupoSolicitudType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('version', ChoiceType::class, [
                                                            'choices'  => [
                                                                            '1.0.1' => '1.0.1'
                                                            ],
                                                        ])
                ->add('codigoEstablecimiento', ChoiceType::class, [
                                                                    'choices'  => [
                                                                        'MIRALEJOS SACIFIyA.' => '1567'
                                                                    ],
                                                                ])
                ->add('exportacion', ChoiceType::class, [
                                                        'choices'  => [
                                                                        'Cons. Interno' => false,
                                                                        'Exportacion' => true
                                                        ],
                                                    ])
                ->add('roleEstablecimiento', ChoiceType::class, [
                                                                    'choices'  => [
                                                                        'FAENADOR' => 'FAENADOR',
                                                                        'ELABORADOR' => 'ELABORADOR',
                                                                        'FAENADOR-ELABORADOR' => 'FAENADOR_ELABORADOR',
                                                                        'DEPOSITO' => 'DEPOSITO',
                                                                        'TRANSPORTE-TRANSBORDO' => 'TRANSPORTE_TRANSBORDO',
                                                                    ],
                                                                ])
                ->add('fecha', DateType::class, ['widget' => 'single_text'])
                ->add('paisDestino')
                ->add('guardar', SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GestionSigcerBundle\Entity\GrupoSolicitud'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gestionsigcerbundle_gruposolicitud';
    }


}
