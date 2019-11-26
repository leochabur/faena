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
use GestionFaenaBundle\Entity\gestionBD\ArticuloProcesoFaena;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use GestionFaenaBundle\Entity\faena\ConceptoMovimiento;
use GestionFaenaBundle\Entity\faena\MovimientoStock;
use Symfony\Component\Form\FormInterface;
class InitMoveStockType extends AbstractType
{

    private $manager;
    /**
     * {@inheritdoc}
     */

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
                $this->manager = $options['manager'];
                $manager = $this->manager;
                $builder->add('movimiento', 
                              ChoiceType::class, 
                              ['mapped' => false, 'choices' => ['Entrada Stock'=> EntradaStock::class, 'Salida Stock'=> SalidaStock::class, 'Transformar Stock'=> TransformarStock::class]])              
                        ->add('artProcFaena', 
                              EntityType::class, 
                              [
                              'class' => ArticuloProcesoFaena::class,
                                ])
                        ->add('guardar', SubmitType::class, ['label' => 'Siguiente >>']); 


                $formModifier = function (FormInterface $form, MovimientoStock $movimiento = null) use ($manager){
                                        $repository = $this->manager->getRepository(ConceptoMovimiento::class);
                                        $options = null === $movimiento ? array() : $movimiento->getConceptos($repository->findAll());

                                        $form->add('concepto', EntityType::class, array(
                                                    'class'       => ConceptoMovimiento::class,
                                                    'choices'     => $options,
                                                  ));


                                    };
                $builder->addEventListener(
                    FormEvents::PRE_SET_DATA,
                    function (FormEvent $event) use ($formModifier) {
                        $data = $event->getData();
                        $formModifier($event->getForm(), $data);
                        $event->getForm()->add('artProcFaena', 
                                                  EntityType::class, 
                                                  [
                                                  'class' => ArticuloProcesoFaena::class,
                                                  'choices' => $data->getProcesoFnDay()->getProcesoFaena()->getArticulos()
                                                    ]);
                    }
                );
                $builder->get('movimiento')->addEventListener(
                    FormEvents::POST_SUBMIT,
                    function (FormEvent $event) use ($formModifier) {
                        $data = $event->getForm()->getData();
                        $data = new $data;
                        $formModifier($event->getForm()->getParent(), $data);
                    }
                );

    }

    /**
     * {@inheritdoc}
     */

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GestionFaenaBundle\Entity\faena\MovimientoStock'
        ));
        $resolver->setRequired('manager');
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
