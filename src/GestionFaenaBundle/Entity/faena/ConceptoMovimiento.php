<?php

namespace GestionFaenaBundle\Entity\faena;

use Doctrine\ORM\Mapping as ORM;

/**
 * ConceptoMovimiento
 *
 * @ORM\Table(name="sp_st_con_mov")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\faena\ConceptoMovimientoRepository")
 */
class ConceptoMovimiento
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
     * @var string
     *
     * @ORM\Column(name="concepto", type="string", length=255)
     */
    private $concepto;

    /**
     * @var integer
     *
     * @ORM\Column(name="esa", type="integer")
     */
    private $esa; ///indica si el concepto se aplica al ingreso, egreso o ambos movimientos de stock

    /**
     * @ORM\OneToMany(targetEntity="AtributoConcepto", mappedBy="concepto")
     */
    private $atributos;


    public function manejaAtributo(\GestionFaenaBundle\Entity\gestionBD\AtributoProceso $atributo)
    {
        foreach ($this->atributos as $atributoConcepto) {
            if ($atributoConcepto->existeAtributo($atributo))
                return true;
        }
        return false;
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
     * Set concepto
     *
     * @param string $concepto
     *
     * @return ConceptoMovimiento
     */
    public function setConcepto($concepto)
    {
        $this->concepto = $concepto;

        return $this;
    }

    /**
     * Get concepto
     *
     * @return string
     */
    public function getConcepto()
    {
        return $this->concepto;
    }

    /**
     * Set esa
     *
     * @param integer $esa
     *
     * @return ConceptoMovimiento
     */
    public function setEsa($esa)
    {
        $this->esa = $esa;

        return $this;
    }

    /**
     * Get esa
     *
     * @return integer
     */
    public function getEsa()
    {
        return $this->esa;
    }

    public function __toString()
    {
        return $this->concepto;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->atributos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add atributo
     *
     * @param \GestionFaenaBundle\Entity\faena\AtributoConcepto $atributo
     *
     * @return ConceptoMovimiento
     */
    public function addAtributo(\GestionFaenaBundle\Entity\faena\AtributoConcepto $atributo)
    {
        $this->atributos[] = $atributo;

        return $this;
    }

    /**
     * Remove atributo
     *
     * @param \GestionFaenaBundle\Entity\faena\AtributoConcepto $atributo
     */
    public function removeAtributo(\GestionFaenaBundle\Entity\faena\AtributoConcepto $atributo)
    {
        $this->atributos->removeElement($atributo);
    }

    /**
     * Get atributos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAtributos()
    {
        return $this->atributos;
    }
}
