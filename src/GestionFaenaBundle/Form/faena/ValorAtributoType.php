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
use GestionFaenaBundle\Entity\gestionBD\Atributo;

class ValorAtributoType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    private $faena, $proceso, $articulo, $type;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->faena = $options['faena'];
        $this->proceso = $options['proceso'];
        $this->articulo = $options['articulo'];
        $this->type = $options['type'];
        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);
    }

    public function onPreSetData(FormEvent $event)
    {
        $valor = $event->getData();
        $form = $event->getForm();
        $form->add('atributo', 
                    EntityType::class, 
                    [
                        'class' => Atributo::class, 
                        'choices' => [$valor->getAtributo()],
                        'attr' => ['class' => 'col-2'],
                        'choice_label' => 'nombre'
                    ]);
        if (ValorNumerico::class == get_class($valor))
        {
            $form->add('unidadMedida', 
                        EntityType::class, 
                        ['attr' => ['class' => 'col-2'], 
                         'class' => 'GestionFaenaBundle\Entity\gestionBD\UnidadMedida', 
                         'choices' => [$valor->getAtributo()->getUnidadMedida()]]);

            $options = ['attr' => ['class' => 'col-2', 'disabled' => $valor->getAtributo()->getManual()], 'required' => true];
            if ($this->type)
            {
                $options['data'] = ($this->proceso?$this->proceso->getStockArticulo($this->faena, $this->articulo):"");
            }
            $form->add('valor', 
                       TextType::class, 
                        $options);
        }
        elseif(ValorTexto::class == get_class($valor))
        {
            $form->add('valor', TextType::class, ['attr' => ['class' => 'col-2']]);
        }
        elseif(ValorExterno::class == get_class($valor))
        {
            $form->add('entidadExterna', EntityType::class, ['attr' => ['class' => 'col-4'], 'class' => $valor->getAtributo()->getClaseExterna()]);
        }
        
    }

    /**
     * {@inheritdoc}
     */

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('faena')->setRequired('proceso')->setRequired('articulo')->setRequired('type');
        $resolver->setDefaults(array(
            'data_class' => 'GestionFaenaBundle\Entity\faena\ValorAtributo',
            'type' => null
        ));
        
    }
}
