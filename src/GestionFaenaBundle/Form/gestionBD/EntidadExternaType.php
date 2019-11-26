<?php

namespace GestionFaenaBundle\Form\gestionBD;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use GestionFaenaBundle\Entity\gestionBD\Granja; 
use GestionFaenaBundle\Entity\gestionBD\Transportista;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use GestionFaenaBundle\Entity\faena\ConceptoMovimiento;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EntidadExternaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('claseExterna', ChoiceType::class, ['choices' => ['Granja'=> Granja::class , 'Transportista' => Transportista::class]]);

        $formModifier = function (FormInterface $form, $data){


                                        $form->add('concepto', EntityType::class, array(
                                            'class'       => ConceptoMovimiento::class,
                                            'label' => 'TATATAT'.($data['claseExterna'])
                                        ));
                                    };
                $builder->addEventListener(
                    FormEvents::PRE_SET_DATA,
                    function (FormEvent $event) use ($formModifier) {
                        $data = $event->getData();
                        $formModifier($event->getForm(), $data);
                    }
                );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gestionfaenabundle_gestionbd_entidad_externa';
    }


}
