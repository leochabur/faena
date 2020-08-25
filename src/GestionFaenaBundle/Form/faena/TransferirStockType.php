<?php

namespace GestionFaenaBundle\Form\faena;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use GestionFaenaBundle\Entity\gestionBD\ArticuloAtributoConcepto;
use GestionFaenaBundle\Entity\faena\ProcesoFaenaDiaria;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class TransferirStockType extends AbstractType
{

    private $faena, $proceso, $articulo, $automatico, $manager;  //FaenaDiaria - ProcesoFaenaDiaria - Articulo - Si el movimiento se genera automaticamente
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->faena = $options['faena'];
        $this->proceso = $options['proceso'];
        $this->articulo = $options['articulo'];
        $this->manager = $options['manager'];
        $transferir = $builder->getData();
        $builder->add('artProcFaena', 
                              EntityType::class, 
                              [
                              'class' => ArticuloAtributoConcepto::class,
                              'choices' => [$transferir->getArtProcFaena()]
                              ])
                ->add('procesoFnDay', 
                              EntityType::class, 
                              [
                              'class' => ProcesoFaenaDiaria::class, 
                              'choices' => [$transferir->getProcesoFnDay()]
                              ])
                ->add('valores', CollectionType::class, [
                    'entry_type' => ValorAtributoType::class,
                    'entry_options' => ['label' => false, 'type' => $transferir->getType(), 'faena' => $this->faena, 'proceso' => $this->proceso, 'articulo' => $this->articulo],
                ])
                ->add('guardar', SubmitType::class);
        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);
    }

    public function onPreSetData(FormEvent $event)
    {
        $valor = $event->getData();
        $form = $event->getForm();
        
        $default = null;
        if ($destinoDefault = $valor->getArtProcFaena()->getProcesosDestino()->first())
        {
          $default = $this->faena->getProceso($destinoDefault->getId());
        }       

        $form->add('concepto', 
                    EntityType::class, 
                    ['class' => 'GestionFaenaBundle\Entity\faena\ConceptoMovimientoProceso', 
                     'choices' => [$valor->getArtProcFaena()->getConcepto()],
                     'mapped' => false
                    ])
              ->add('destino', 
                    EntityType::class, 
                    ['class' => 'GestionFaenaBundle\Entity\faena\ProcesoFaenaDiaria', 
                     'data' => $default,
                    'choices' => $this->faena->getProcesosDestinos($valor->getProcesoFnDay()->getProcesoFaena()),
                    'mapped' => false,                    
                    'required' => true
                    ]);

        
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GestionFaenaBundle\Entity\faena\TransferirStock'
        ));
        $resolver->setRequired('faena')
                 ->setRequired('proceso')
                 ->setRequired('manager')
                 ->setRequired('articulo'); //para poder recuperar cuales son los ProcesosDiarios Instanciados
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gestionfaenabundle_faena_transferirstock';
    }


}
