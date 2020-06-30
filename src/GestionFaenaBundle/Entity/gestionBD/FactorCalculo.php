<?php

namespace GestionFaenaBundle\Entity\gestionBD;

use Doctrine\ORM\Mapping as ORM;

/**
 * FactorCalculo
 *
 * @ORM\Table(name="sp_gst_fc_clc")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\gestionBD\FactorCalculoRepository")
 */
class FactorCalculo
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
    * @ORM\ManyToOne(targetEntity="Articulo") 
    * @ORM\JoinColumn(name="id_art", referencedColumnName="id")
    */      
    private $articulo;

    /**
    * @ORM\ManyToOne(targetEntity="AtributoAbstracto") 
    * @ORM\JoinColumn(name="id_atr_abst", referencedColumnName="id")
    */      
    private $atributo;

    /** 
    * @ORM\Column(type="string", columnDefinition="ENUM('S', 'P', 'U')") 
    */
    private $tipoCalculo;

    /**
     * @var boolean
     *
     * @ORM\Column(name="soloIngreso", type="boolean", nullable=false)
     */
    private $soloIngreso = false;  //define si solo se debe realizar el calculo sobre los ingresos unicamente o sobre todos los movimientos, para el caso que se deba calcular las transformaciones X ej de Aves a Corazon, tome solo el ingreso

    /**
     * @ORM\ManyToMany(targetEntity="GestionFaenaBundle\Entity\faena\ConceptoMovimiento")
     * @ORM\JoinTable(name="sp_gst_exc_cn_fc",
     *      joinColumns={@ORM\JoinColumn(name="id_fc", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_cm", referencedColumnName="id")}
     *      )
     */
    private $conceptosExcentos;

    public function getAction()
    {
        $action = '';
        switch ($this->tipoCalculo) 
        {
            case 'S':
                    $action = 'ACUMULADO';
                break;
            case 'P':
                    $action = 'PROMEDIO';
                break;
            case 'U':
                    $action = 'UNITARIO';
                break;
        }
        return $action .' ('.$this->atributo.' DE '.$this->articulo.')'.($this->soloIngreso?'(Ingresos)':'(Todos)');
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
     * Set articulo
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\Articulo $articulo
     *
     * @return FactorCalculo
     */
    public function setArticulo(\GestionFaenaBundle\Entity\gestionBD\Articulo $articulo = null)
    {
        $this->articulo = $articulo;

        return $this;
    }

    /**
     * Get articulo
     *
     * @return \GestionFaenaBundle\Entity\gestionBD\Articulo
     */
    public function getArticulo()
    {
        return $this->articulo;
    }

    /**
     * Set atributo
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto $atributo
     *
     * @return FactorCalculo
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

    public function __toString()
    {
        return $this->atributo."";
    }

    /**
     * Set tipoCalculo
     *
     * @param string $tipoCalculo
     *
     * @return FactorCalculo
     */
    public function setTipoCalculo($tipoCalculo)
    {
        $this->tipoCalculo = $tipoCalculo;

        return $this;
    }

    /**
     * Get tipoCalculo
     *
     * @return string
     */
    public function getTipoCalculo()
    {
        return $this->tipoCalculo;
    }

    /**
     * Set soloIngreso
     *
     * @param boolean $soloIngreso
     *
     * @return FactorCalculo
     */
    public function setSoloIngreso($soloIngreso)
    {
        $this->soloIngreso = $soloIngreso;

        return $this;
    }

    /**
     * Get soloIngreso
     *
     * @return boolean
     */
    public function getSoloIngreso()
    {
        return $this->soloIngreso;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->conceptosExcentos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add conceptosExcento
     *
     * @param \GestionFaenaBundle\Entity\faena\ConceptoMovimiento $conceptosExcento
     *
     * @return FactorCalculo
     */
    public function addConceptosExcento(\GestionFaenaBundle\Entity\faena\ConceptoMovimiento $conceptosExcento)
    {
        $this->conceptosExcentos[] = $conceptosExcento;

        return $this;
    }

    /**
     * Remove conceptosExcento
     *
     * @param \GestionFaenaBundle\Entity\faena\ConceptoMovimiento $conceptosExcento
     */
    public function removeConceptosExcento(\GestionFaenaBundle\Entity\faena\ConceptoMovimiento $conceptosExcento)
    {
        $this->conceptosExcentos->removeElement($conceptosExcento);
    }

    /**
     * Get conceptosExcentos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getConceptosExcentos()
    {
        return $this->conceptosExcentos;
    }
}
