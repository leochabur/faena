<?php

namespace GestionFaenaBundle\Entity\gestionBD;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use GestionFaenaBundle\Entity\faena\ValorTexto;
/**
 * AtributoInformableArbitrario
 *
 * @ORM\Table(name="sp_gst_atr_inf_arb")
  * @UniqueEntity(
 *     fields={"articuloAtrConc", "atributoAbstracto"},
 *     message="Atributo ya asignado al articulo!!!"
 * )
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\gestionBD\AtributoInformableArbitrarioRepository")
 */
class AtributoInformableArbitrario extends AtributoInformable
{
    
    public function getEntityValorAtributo(\GestionFaenaBundle\Entity\gestionBD\Atributo $atributo)
    {
        //throw new \Exception("valor  ".get_class($atributo->getAtributoAbstracto()), 1);
        $value = new ValorTexto();
        $value->setAtributo($atributo);
        return $value;
    }

    public function getClass()
    {
        return get_class($this);
    }
}
