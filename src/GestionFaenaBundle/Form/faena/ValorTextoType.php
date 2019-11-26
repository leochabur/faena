<?php

namespace GestionFaenaBundle\Form\faena;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ValorTextoType extends ValorAtributoType
{


        public function getDataClass()
    {
        return 'GestionFaenaBundle\Entity\faena\ValorTexto';
    }

}
