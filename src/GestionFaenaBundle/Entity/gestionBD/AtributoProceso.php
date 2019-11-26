<?php

namespace GestionFaenaBundle\Entity\gestionBD;

use Doctrine\ORM\Mapping as ORM;

/**
 * AtributoProceso
 *
 * @ORM\Table(name="sp_atr_proc")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\gestionBD\AtributoProcesoRepository")
 */
class AtributoProceso
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="orden", type="integer")
     */
    private $orden;

    /**
     * @var int
     *
     * @ORM\Column(name="decimales", type="integer", options={"default"=-1})
     */
    private $decimales = -1;

    /**
     * @var boolean
     *
     * @ORM\Column(name="mostrar", type="boolean")
     */
    private $mostrar;

    /**
     * @var boolean
     *
     * @ORM\Column(name="acumula", type="boolean")
     */
    private $acumula = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="promedia", type="boolean")
     */
    private $promedia = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="controlaStock", type="boolean")
     */
    private $controlaStock;
    
    /**
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\gestionBD\Atributo") 
    * @ORM\JoinColumn(name="id_atr", referencedColumnName="id")
    */   
    private $atributo;

    
    public function __toString()
    {
        return $this->atributo.'';
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set orden
     *
     * @param integer $orden
     *
     * @return AtributoProceso
     */
    public function setOrden($orden)
    {
        $this->orden = $orden;

        return $this;
    }

    /**
     * Get orden
     *
     * @return int
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * Set mostrar
     *
     * @param boolean $mostrar
     *
     * @return AtributoProceso
     */
    public function setMostrar($mostrar)
    {
        $this->mostrar = $mostrar;

        return $this;
    }

    /**
     * Get mostrar
     *
     * @return bool
     */
    public function getMostrar()
    {
        return $this->mostrar;
    }

    /**
     * Set atributo
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\Atributo $atributo
     *
     * @return AtributoProceso
     */
    public function setAtributo(\GestionFaenaBundle\Entity\gestionBD\Atributo $atributo = null)
    {
        $this->atributo = $atributo;

        return $this;
    }

    /**
     * Get atributo
     *
     * @return \GestionFaenaBundle\Entity\gestionBD\Atributo
     */
    public function getAtributo()
    {
        return $this->atributo;
    }

    /**
     * Set controlaStock
     *
     * @param boolean $controlaStock
     *
     * @return AtributoProceso
     */
    public function setControlaStock($controlaStock)
    {
        $this->controlaStock = $controlaStock;

        return $this;
    }

    /**
     * Get controlaStock
     *
     * @return boolean
     */
    public function getControlaStock()
    {
        return $this->controlaStock;
    }

    /**
     * Set acumula
     *
     * @param boolean $acumula
     *
     * @return AtributoProceso
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
     * @return AtributoProceso
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

    /**
     * Set decimales
     *
     * @param integer $decimales
     *
     * @return AtributoProceso
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
}
