<?php

namespace GestionFaenaBundle\Form\gestionBD;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use GestionFaenaBundle\Entity\gestionBD\AtributoProceso;

class EditArtAtrConType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('proceso', 
                      EntityType::class,
                      [
                        'class' => 'GestionFaenaBundle\Entity\ProcesoFaena',
                        'mapped' => false,
                        'choices_as_values' => true
                      ])
                ->addEventListener(FormEvents::PRE_SET_DATA , [$this, 'onPreSetData']);
    }

    public function onPreSetData(FormEvent $event)
    {
        //$valor = $event->getData();
        $form = $event->getForm();
        $proc = $form->get('proceso')->getData();
        throw new \Exception("Error Processing Request ".$proc->getId(), 1);
        
        //if (!$valor)
            $form->add('atributo', 
                        EntityType::class, 
                        ['class' => AtributoProceso::class]);
        
    }


    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GestionFaenaBundle\Entity\gestionBD\ArticuloAtributoConcepto'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gestionfaenabundle_gestionbd_articuloatributoconcepto';
    }


}
