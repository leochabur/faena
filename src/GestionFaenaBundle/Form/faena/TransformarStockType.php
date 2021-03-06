<?php

namespace GestionFaenaBundle\Form\faena;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use GestionFaenaBundle\Entity\gestionBD\ArticuloProcesoFaena;
use GestionFaenaBundle\Entity\faena\ProcesoFaenaDiaria;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use GestionFaenaBundle\Entity\gestionBD\ArticuloAtributoConcepto;

class TransformarStockType extends AbstractType
{

    private $faena, $proceso, $articulo;  //FaenaDiaria - ProcesoFaenaDiaria - Articulo
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->faena = $options['faena'];
        $this->proceso = $options['proceso'];
        $this->articulo = $options['articulo'];

        $entrada = $builder->getData();
        $builder->add('artProcFaena', 
                              EntityType::class, 
                              [
                              'class' => ArticuloAtributoConcepto::class,
                              'choices' => [$entrada->getArtProcFaena()]
                                ])
                ->add('procesoFnDay', 
                              EntityType::class, 
                              [
                              'class' => ProcesoFaenaDiaria::class, 
                              'choices' => [$entrada->getProcesoFnDay()]
                                ])
                ->add('valores', CollectionType::class, [
                    'entry_type' => ValorAtributoType::class,
                    'entry_options' => ['label' => false, 'type' => $entrada->getType(), 'faena' => $this->faena, 'proceso' => $this->proceso, 'articulo' => $this->articulo],
                ])
                ->add('guardar', SubmitType::class);
                $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);
    }

    public function onPreSetData(FormEvent $event)
    {
        $valor = $event->getData();
        $form = $event->getForm();

            $form->add('concepto', 
                       EntityType::class, 
                       ['class' => 'GestionFaenaBundle\Entity\faena\ConceptoMovimientoProceso', 
                       'choices' => [$valor->getArtProcFaena()->getConcepto()],
                        'mapped' => false
                      ]);

        
    }

    /**
     * {@inheritdoc}
     */
/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GestionFaenaBundle\Entity\faena\TransformarStock'
        ));
        $resolver->setRequired('faena')->setRequired('proceso')->setRequired('articulo'); 
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gestionfaenabundle_faena_transformarstock';
    }


}
