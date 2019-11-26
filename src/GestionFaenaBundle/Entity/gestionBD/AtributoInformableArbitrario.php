<?php

namespace GestionFaenaBundle\Entity\gestionBD;

use Doctrine\ORM\Mapping as ORM;
use GestionFaenaBundle\Entity\faena\ValorTexto;
/**
 * AtributoInformableArbitrario
 *
 * @ORM\Table(name="sp_gst_atr_inf_arb")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\gestionBD\AtributoInformableArbitrarioRepository")
 */
class AtributoInformableArbitrario extends AtributoInformable
{
    
    public function getEntityValorAtributo($atributo)
    {
        $value = new ValorTexto();
        $value->setAtributo($atributo);
        return $value;
    }

    public function getClass()
    {
        return get_class($this);
    }
}
