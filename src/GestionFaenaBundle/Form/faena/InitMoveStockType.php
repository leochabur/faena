<?php

namespace GestionFaenaBundle\Form\faena;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use GestionFaenaBundle\Entity\faena\EntradaStock;
use GestionFaenaBundle\Entity\faena\SalidaStock;
use GestionFaenaBundle\Entity\faena\TransformarStock;
use GestionFaenaBundle\Entity\faena\ConceptoMovimientoProceso;

use GestionFaenaBundle\Entity\gestionBD\ArticuloAtributoConcepto;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use GestionFaenaBundle\Entity\faena\ConceptoMovimiento;
use GestionFaenaBundle\Entity\faena\MovimientoStock;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use GestionFaenaBundle\Entity\faena\TipoMovimientoConcepto;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;


class InitMoveStockType extends AbstractType
{

    private $manager;
    private $proceso;
    /**
     * {@inheritdoc}
     */

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
                $this->manager = $options['manager'];
                $manager = $this->manager;
                $proceso = $options['proceso'];
               /* $builder->add('movimiento', 
                              ChoiceType::class, 
                              ['mapped' => false, 
                               'choices' => ['Entrada'=> EntradaStock::class, 'Salida'=> SalidaStock::class, 'Transformar'=> TransformarStock::class],
                               'required' => false
                             ])*/
                $builder->add('tipoMovimiento', 
                               EntityType::class, 
                               ['class'       => TipoMovimientoConcepto::class,
                                'mapped' => false, 
                                'required' => false
                              ])
                        ->add('concepto', EntityType::class, array(
                                                    'class'       => ConceptoMovimientoProceso::class,
                                                    'choices'     => [],
                                                    'mapped' => false
                                                  ))
                        ->add('artProcFaena', 
                                                  EntityType::class, 
                                                  [
                                                  'class' => ArticuloAtributoConcepto::class,
                                                  'choices'     => [],
                                                  'mapped' => false
                                                    ])
                        ->add('proceso', HiddenType::class, [
                                                              'data' => $proceso->getId(),
                                                              'mapped' => false
                                                          ])
                        ->add('guardar', SubmitType::class, ['label' => 'Siguiente >>']); 
                $formModifier = function (FormInterface $form, MovimientoStock $movimiento = null, $f = null) use ($manager, $proceso){
                                        $options = null === $movimiento ? array() : $proceso->getProcesoFaena()->getConceptos();

                                        $form->add('concepto', EntityType::class, array(
                                                    'class'       => ConceptoMovimientoProceso::class,
                                                    'choices'     => $options,
                                                    'choice_label' => 'name',
                                                  ));
                                        $accessor = PropertyAccess::createPropertyAccessor();
                                        $data = $event->getData();
                                        $concepto = $accessor->getValue($data, 'mascota');
                                        $concepto = $f?$f->getData()['concepto']:null;
                                        $form->add('artProcFaena', 
                                                  EntityType::class, 
                                                  [
                                                  'class' => ArticuloAtributoConcepto::class,
                                                  'placeholder' => get_class($concepto).','
                                                    ]);
                                    };


             /*   $builder->addEventListener(
                    FormEvents::PRE_SET_DATA,
                    function (FormEvent $event) use ($formModifier) {
                        $data = $event->getData();
                        $form = $event->getForm();
                        $form->add('concepto', EntityType::class, array(
                                                    'class'       => ConceptoMovimientoProceso::class,
                                                   
                                                    'choice_label' => 'name',
                                                  ));
                        $concepto = $form->get('concepto')->getData()->get('concepto');
                        $form->add('artProcFaena', 
                                                  EntityType::class, 
                                                  [
                                                  'class' => ArticuloAtributoConcepto::class,
                                                  'placeholder' => get_class($concepto).','
                                                    ]);
                    }
                );
                $builder->get('movimiento')->addEventListener(
                    FormEvents::POST_SUBMIT,
                    function (FormEvent $event) use ($formModifier) {
                        $data = $event->getForm()->getData();
                        $data = new $data;
                        $formModifier($event->getForm()->getParent(), $data, $event->getForm());
                    }
                );*/

              /*
                                      ->add('concepto', 
                                EntityType::class, 
                                [
                                'class'       => ConceptoMovimientoProceso::class,
                                 'choices'     => [],
                              ])
                        ->add('artProcFaena', 
                                                  EntityType::class, 
                                                  [
                                                  'class' => ArticuloAtributoConcepto::class,
                                                  'choices' => []
                                                    ])*/

    }

    /**
     * {@inheritdoc}
     */

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GestionFaenaBundle\Entity\faena\MovimientoStock'
        ));
        $resolver->setRequired('manager')->setRequired('proceso');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gestionfaenabundle_faena_initmovest';
    }

    /*
                                  'query_builder' => function (ArticuloProcesoFaenaRepository $er) use ($proc) {
                                                                                                                   return $er->createQueryBuilder('a')->where('a.proceso = :proceso')->setParameter('proceso', $proc->getProcesoFaena());
                                                                                                              }*/
}
