<?php

namespace GestionFaenaBundle\Entity\gestionBD;

use Doctrine\ORM\Mapping as ORM;
use GestionFaenaBundle\Entity\faena\ValorExterno;
/**
 * AtributoInformableExterno
 *
 * @ORM\Table(name="sp_gst_atr_inf_ext")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\gestionBD\AtributoInformableExternoRepository")
 */
class AtributoInformableExterno extends AtributoInformable
{
    /**
     * @var string
     *
     * @ORM\Column(name="claseExterna", type="string")
     */
    private $claseExterna;

    /**
     * Set claseExterna
     *
     * @param string $claseExterna
     *
     * @return AtributoInformableExterno
     */
    public function setClaseExterna($claseExterna)
    {
        $this->claseExterna = $claseExterna;

        return $this;
    }

    /**
     * Get claseExterna
     *
     * @return string
     */
    public function getClaseExterna()
    {
        return $this->claseExterna;
    }

    public function getEntityValorAtributo($atributo)
    {
        $value = new ValorExterno();
        $value->setAtributo($atributo);
        return $value;
    }

    public function getClass()
    {
        return get_class($this);
    }
}
