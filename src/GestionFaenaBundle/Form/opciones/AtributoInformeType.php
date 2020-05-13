<?php

namespace GestionFaenaBundle\Form\opciones;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AtributoInformeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('posicion')
                ->add('atributo')
                ->add('visible')
                ->add('agrupa')
                ->add('sumariza')
                ->add('promedia')
                ->add('numeroOrden')
                ->add('guardar', SubmitType::class)
                ->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GestionFaenaBundle\Entity\opciones\AtributoInforme'
        ));
    }

    public function onPreSetData(FormEvent $event)
    {
        $valor = $event->getData();
        $form = $event->getForm();
        $form->add('informe', 
                    EntityType::class, 
                    ['class' => 'GestionFaenaBundle\Entity\opciones\InformeProceso', 
                     'choices' => [$valor->getInforme()]
                    ]);

        
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gestionfaenabundle_opciones_atributoinforme';
    }


}
