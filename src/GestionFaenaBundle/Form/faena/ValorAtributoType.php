<?php

namespace GestionFaenaBundle\Form\faena;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use GestionFaenaBundle\Entity\faena\ValorNumerico;
use GestionFaenaBundle\Entity\faena\ValorTexto;
use GestionFaenaBundle\Entity\faena\ValorExterno;
use GestionFaenaBundle\Entity\gestionBD\AtributoProceso;

class ValorAtributoType extends AbstractType
{

    /**
     * {@inheritdoc}
     */

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);
    }

    public function onPreSetData(FormEvent $event)
    {
        $valor = $event->getData();
        $form = $event->getForm();
        $form->add('atributo', EntityType::class, ['class' => AtributoProceso::class, 'choices' => [$valor->getAtributo()],'attr' => ['class' => 'col-2']]);
        if (ValorNumerico::class == get_class($valor))
        {
            $form->add('unidadMedida', EntityType::class, ['attr' => ['class' => 'col-2'], 'class' => 'GestionFaenaBundle\Entity\gestionBD\UnidadMedida', 'choices' => [$valor->getAtributo()->getAtributo()->getUnidadMedida()]]);
            $form->add('valor', TextType::class, ['attr' => ['class' => 'col-2', 'disabled' => $valor->getAtributo()->getAtributo()->getManual()]]);
        }
        elseif(ValorTexto::class == get_class($valor))
        {
            $form->add('valor', TextType::class, ['attr' => ['class' => 'col-2']]);
        }
        elseif(ValorExterno::class == get_class($valor))
        {
            $form->add('entidadExterna', EntityType::class, ['attr' => ['class' => 'col-4'], 'class' => $valor->getAtributo()->getAtributo()->getClaseExterna()]);
        }
        
    }

    /**
     * {@inheritdoc}
     */

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GestionFaenaBundle\Entity\faena\ValorAtributo'
        ));
    }
}
