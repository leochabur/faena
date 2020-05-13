<?php

namespace GestionFaenaBundle\Entity\opciones;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PosicionAtributoInforme
 *
 * @ORM\Table(name="opciones_posicion_atributo_informe")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\opciones\AtributoInformeRepository")
 */
class AtributoInforme
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
     * @ORM\Column(name="posicion", type="integer")
     */
    private $posicion; // la posicion en la que debe aparecer el campo en la tabla

    /**
     * @var boolean
     *
     * @ORM\Column(name="visible", type="boolean", options={"default" = true})
     */
    private $visible = true;

    /**
     * @var boolean
     *
     * @ORM\Column(name="agrupa", type="boolean", options={"default" = false})
     */
    private $agrupa = false; //indica si se debe agrupar por este campo

    /**
     * @var boolean
     *
     * @ORM\Column(name="sumariza", type="boolean", options={"default" = false})
     */
    private $sumariza = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="promedia", type="boolean", options={"default" = false})
     */
    private $promedia = false; 

    /**
     * @var int
     *
     * @ORM\Column(name="numOrden", type="integer", nullable=true)
     */
    private $numeroOrden; //indica en que lugar se posiciona para realizar el orden de los datos  EJ:: Granja -> Orden 1   -   Cargador -> Orden 2

    /**
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto") 
    * @ORM\JoinColumn(name="id_atr_abst", referencedColumnName="id")
    * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
    */      
    private $atributo;

    /**
    * @ORM\ManyToOne(targetEntity="InformeProceso", inversedBy="atributos") 
    * @ORM\JoinColumn(name="id_inf_proc", referencedColumnName="id")
    * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
    */      
    private $informe;

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
     * Set posicion
     *
     * @param integer $posicion
     *
     * @return PosicionAtributoInforme
     */
    public function setPosicion($posicion)
    {
        $this->posicion = $posicion;

        return $this;
    }

    /**
     * Get posicion
     *
     * @return int
     */
    public function getPosicion()
    {
        return $this->posicion;
    }

    /**
     * Set informe
     *
     * @param \GestionFaenaBundle\Entity\opciones\InformeProceso $informe
     *
     * @return AtributoInforme
     */
    public function setInforme(\GestionFaenaBundle\Entity\opciones\InformeProceso $informe = null)
    {
        $this->informe = $informe;

        return $this;
    }

    /**
     * Get informe
     *
     * @return \GestionFaenaBundle\Entity\opciones\InformeProceso
     */
    public function getInforme()
    {
        return $this->informe;
    }

    /**
     * Set atributo
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto $atributo
     *
     * @return AtributoInforme
     */
    public function setAtributo(\GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto $atributo = null)
    {
        $this->atributo = $atributo;

        return $this;
    }

    /**
     * Get atributo
     *
     * @return \GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto
     */
    public function getAtributo()
    {
        return $this->atributo;
    }

    /**
     * Set numeroOrden
     *
     * @param integer $numeroOrden
     *
     * @return AtributoInforme
     */
    public function setNumeroOrden($numeroOrden)
    {
        $this->numeroOrden = $numeroOrden;

        return $this;
    }

    /**
     * Get numeroOrden
     *
     * @return integer
     */
    public function getNumeroOrden()
    {
        return $this->numeroOrden;
    }

    /**
     * Set visible
     *
     * @param boolean $visible
     *
     * @return AtributoInforme
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get visible
     *
     * @return boolean
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Set agrupa
     *
     * @param boolean $agrupa
     *
     * @return AtributoInforme
     */
    public function setAgrupa($agrupa)
    {
        $this->agrupa = $agrupa;

        return $this;
    }

    /**
     * Get agrupa
     *
     * @return boolean
     */
    public function getAgrupa()
    {
        return $this->agrupa;
    }

    /**
     * Set sumariza
     *
     * @param boolean $sumariza
     *
     * @return AtributoInforme
     */
    public function setSumariza($sumariza)
    {
        $this->sumariza = $sumariza;

        return $this;
    }

    /**
     * Get sumariza
     *
     * @return boolean
     */
    public function getSumariza()
    {
        return $this->sumariza;
    }

    /**
     * Set promedia
     *
     * @param boolean $promedia
     *
     * @return AtributoInforme
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
