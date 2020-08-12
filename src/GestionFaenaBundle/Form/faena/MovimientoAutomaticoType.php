<?php

namespace GestionFaenaBundle\Form\faena;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MovimientoAutomaticoType extends AbstractType
{
    private $proceso;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->proceso = $options['proceso'];
        $builder->add('ordenEjecucion', 
                      TextType::class,
                      ['data' => ($this->proceso->getAutomaticos()->count()+1)])
                ->add('guardar', SubmitType::class, ['label' => '+'])
                ->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);
    }


    public function onPreSetData(FormEvent $event)
    {
        $proceso = $this->proceso;
        $form = $event->getForm();
        $form->add('articuloAtributoConcepto', 
                    EntityType::class, [
                                      'class' => 'GestionFaenaBundle:gestionBD\ArticuloAtributoConcepto',         
                                      'choice_label' => 'vistaEdicion',                    
                                      'query_builder' => function (EntityRepository $er) use ($proceso){
                                                                                                        return $er->createQueryBuilder('a')
                                                                                                                  ->join('a.concepto', 'c')
                                                                                                                  ->join('c.procesoFaena', 'p')
                                                                                                                  ->where('p = :proceso')
                                                                                                                  ->andWhere('a.activo = :activo')
                                                                                                                  ->setParameter('activo', true)
                                                                                                                  ->setParameter('proceso', $proceso);
                                                                                                        }
                 ])
            ->add('procesoDestinoDefault', 
                    EntityType::class, [
                                      'class' => 'GestionFaenaBundle:ProcesoFaena',         
                                      'choices' => $proceso->getProcesosDestino(),
                                      'required' => false
                 ])
                ->add('procesoFaena', 
                      EntityType::class, 
                      [
                          'class' => 'GestionFaenaBundle:ProcesoFaena', 
                          'choices' => [$proceso]
                      ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
                    'data_class' => 'GestionFaenaBundle\Entity\faena\MovimientoAutomatico'
                ))
                ->setRequired('proceso');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gestionfaenabundle_faena_movimientoautomatico';
    }


}
