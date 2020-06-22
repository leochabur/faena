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
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

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
        if (!$valor->getAtributo()->getMostrarAlCargar()) //para los casos que el ususrio comun no vea los calculos que se haceb
        {
            $form->add('hidden', 
                       HiddenType::class, 
                       ['mapped' => false]);
        }

        if (ValorNumerico::class == get_class($valor))
        {
            /*$form->add('unidadMedida', 
                        EntityType::class, 
                        ['attr' => ['class' => 'col-2'], 
                         'class' => 'GestionFaenaBundle\Entity\gestionBD\UnidadMedida', 
                         'choices' => [$valor->getAtributo()->getUnidadMedida()]]);*/
            $class = 'col-2';

            if (strtoupper($valor->getAtributo()->getNombre()) == 'DT-E') // para aplicar el formato de entrada delatributo
                $class.=', dt-e';
            $options = ['attr' => ['class' => $class, 'disabled' => $valor->getAtributo()->getManual()], 'required' => true];

            if ($this->type)
            {
                $data = '';
                if ($this->proceso)
                {
                    $val = null;
                    if (!$valor->getAtributo()->getManual())
                        $val = $this->proceso->getStockArticulo($this->faena, $this->articulo, $valor->getAtributo()->getAtributoAbstracto());
                    $data = $val; //, $valor->getAtributo()->getDecimales());
                }
                
                $options['data'] = $data;
            }
            elseif (!$valor->getAtributo()->getMostrarAlCargar())
            {
                if ($valor->getValor())
                    $options['data'] = $valor->getValor();
                else
                    $options['data'] = ($valor->getAtributo()->getDefecto()?$valor->getAtributo()->getDefecto():'');
            }
            $form->add('valor', 
                       TextType::class, 
                        $options);
        }
        elseif(ValorTexto::class == get_class($valor))
        {

            if (strtoupper($valor->getAtributo()->getNombre()) == 'DT-E') // para aplicar el formato de entrada delatributo
            {
                $form->add('class', 
                           HiddenType::class, 
                           ['mapped' => false, 'data'=> 'dte']);
            }
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
