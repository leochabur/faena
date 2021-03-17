<?php

namespace GestionFaenaBundle\Form\gestionBD;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ArticuloType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    private $user;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->user = $options['user'];

        $faena = [];
        if (!$this->user->isPerfilFaena())
        {
            $faena = [
                        'attr' => [
                                    'readonly' => true,
                                  ]
                     ];
        }

        $ventas = [];
        $builder->add('nombre', TextType::class, $faena)
                ->add('nombreResumido', TextType::class, $faena)
                ->add('codigoInterno', TextType::class, $faena)
                ->add('presentacionKg', TextType::class, $faena)
                ->add('presentacionUnidad', TextType::class, $faena)
                ->add('descVenta', TextType::class, $ventas)
                ->add('guardar', SubmitType::class)
                ->addEventListener(
                                        FormEvents::PRE_SET_DATA,
                                        [$this, 'onPreSetData']
                                    );
    }

    public function onPreSetData(FormEvent $event)
    {
        $articulo = $event->getData();
        $form = $event->getForm();

        $faena = [];
        if (!$this->user->isPerfilFaena())
        {
            $faena = [
                        'attr' => [
                                    'readonly' => true,
                                  ]                         
                     ];
        }

        if (true)
        {
            $form->add('categoria', 
                       EntityType::class, 
                       [
                        'class' => 'GestionFaenaBundle\Entity\gestionBD\CategoriaArticulo',
                        'required' => false
                       ])
                ->add('subcategoria', 
                      EntityType::class, 
                      ['class' => 'GestionFaenaBundle\Entity\gestionBD\SubcategoriaArticulo',
                       'required' => false])
                ->add('articuloBase', 
                      EntityType::class, 
                      ['class' => 'GestionFaenaBundle\Entity\gestionBD\Articulo',
                       'required' => false])
                 ->add('clasificable')
                 ->add('orden', TextType::class);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GestionFaenaBundle\Entity\gestionBD\Articulo'
                ))
                ->setRequired('user');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gestionfaenabundle_articulo';
    }


}
