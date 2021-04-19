<?php

namespace GestionFaenaBundle\Form\exportacion;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class TipoPalletType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('tipo')
                ->add('capacidad')
                ->add('articulos', EntityType::class,
                     [
                        'class' => 'GestionFaenaBundle\Entity\gestionBD\Articulo',
                        'multiple' => true,
                        'query_builder' => function (EntityRepository $er){
                                                          return $er->createQueryBuilder('a')
                                                                    ->join('a.categoria', 'c')
                                                                    ->join('a.subcategoria', 's')
                                                                    ->where('a.eliminado = :eliminado')
                                                                    ->setParameter('eliminado', false)
                                                                    ->orderBy('c.orden')
                                                                    ->addOrderBy('s.orden')
                                                                    ->addOrderBy('a.nombre');
                                                                  },
                        'group_by' => function($choice, $key, $value) {
                                                                            return $choice->getCategoria().'-'.$choice->getSubcategoria();
                                                                       },
                     ])
                ->add('guardar', SubmitType::class)
                ->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);
    }

    public function onPreSetData(FormEvent $event)
    {
        $valor = $event->getData();
        $form = $event->getForm();
        if ($valor->getId())
        {
        $form->add('activo');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GestionFaenaBundle\Entity\exportacion\TipoPallet'
        ));
    }


    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gestionfaenabundle_exportacion_tipopallet';
    }


}
