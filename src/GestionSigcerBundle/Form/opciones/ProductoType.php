<?php

namespace GestionSigcerBundle\Form\opciones;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProductoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nombre')
                ->add('codigoCapa')
                ->add('marca')
                ->add('envasePrimario')
                ->add('envaseSecundario')      
                ->add('ajusteBruto')
                ->add('ajusteNeto')        
                 ->add('ajusteCantidad') 
                ->add('destino', ChoiceType::class, [
                                                        'choices'  => [
                                                            'Mercado Interno' => 'MI',
                                                            'Exportacion' => 'EX'
                                                        ],
                                                    ])        
    ->add('guardar', SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GestionSigcerBundle\Entity\opciones\Producto'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gestionsigcerbundle_opciones_producto';
    }


}
