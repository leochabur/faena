<?php

namespace GestionFaenaBundle\Entity\gestionBD;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use GestionFaenaBundle\Entity\faena\ValorNumerico;
/**
 * AtributoMedible
 *
 * @ORM\Table(name="sp_gst_atr_med")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\gestionBD\AtributoMedibleRepository")
 */
abstract class AtributoMedible extends Atributo
{


    /**
    * @ORM\ManyToOne(targetEntity="UnidadMedida") 
    * @ORM\JoinColumn(name="id_unt_med", referencedColumnName="id")
    * @Assert\NotNull(message="En campo no puede permanecer en blanco!")
    */      
    private $unidadMedida;
    
    /**
     * @var int
     *
     * @ORM\Column(name="decimales", type="integer", options={"default":0})
     */
    private $decimales = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="acumula", type="boolean", options={"default":false})
     */
    private $acumula = false;

    /**
     * @var string
     *
     * @ORM\Column(name="promedia", type="boolean", options={"default":false})
     */
    private $promedia = false;

    /**
     * Set unidadMedida
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\UnidadMedida $unidadMedida
     *
     * @return AtributoMedible
     */
    public function setUnidadMedida(\GestionFaenaBundle\Entity\gestionBD\UnidadMedida $unidadMedida = null)
    {
        $this->unidadMedida = $unidadMedida;

        return $this;
    }

    /**
     * Get unidadMedida
     *
     * @return \GestionFaenaBundle\Entity\gestionBD\UnidadMedida
     */
    public function getUnidadMedida()
    {
        return $this->unidadMedida;
    }



    public function getTipo()
    {
        return 'Medible';
    }

    public function getAjuste()
    {
        return $this->factorAjuste;
    }

    public function getEntityValorAtributo(\GestionFaenaBundle\Entity\gestionBD\Atributo $atributo)
    {
        $value = new ValorNumerico();
        $value->setUnidadMedida($this->unidadMedida);
        $value->setAtributo($atributo);
        return $value;
    }

    public function getType()
    {
        return 1;
    }

    public function manejaDecimales()
    {
        return true;
    }

    /**
     * Set decimales
     *
     * @param integer $decimales
     *
     * @return AtributoMedible
     */
    public function setDecimales($decimales)
    {
        $this->decimales = $decimales;

        return $this;
    }

    /**
     * Get decimales
     *
     * @return integer
     */
    public function getDecimales()
    {
        return $this->decimales;
    }

    /**
     * Set acumula
     *
     * @param boolean $acumula
     *
     * @return AtributoMedible
     */
    public function setAcumula($acumula)
    {
        $this->acumula = $acumula;

        return $this;
    }

    /**
     * Get acumula
     *
     * @return boolean
     */
    public function getAcumula()
    {
        return $this->acumula;
    }

    /**
     * Set promedia
     *
     * @param boolean $promedia
     *
     * @return AtributoMedible
     */
    public function setPromedia($promedia)
    {
        $this->promedia = $promedia;

        return $this;
    }

    /**
     * Get promedia
     *
     * @return boolean
     */
    public function getPromedia()
    {
        return $this->promedia;
    }
}
