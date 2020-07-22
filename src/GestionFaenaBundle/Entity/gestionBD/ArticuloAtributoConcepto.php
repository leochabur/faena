<?php

namespace GestionFaenaBundle\Entity\gestionBD;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * ArticuloAtributoConcepto
 *
 * @ORM\Table(name="sp_st_art_atr_con")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\gestionBD\ArticuloAtributoConceptoRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ArticuloAtributoConcepto
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
    * @ORM\ManyToOne(targetEntity="Articulo", inversedBy="artsAtrConc") 
    * @ORM\JoinColumn(name="id_articulo", referencedColumnName="id")
    */      
    private $articulo;

    /**
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\faena\ConceptoMovimientoProceso", inversedBy="articulos") 
    * @ORM\JoinColumn(name="id_concepto", referencedColumnName="id")
    */      
    private $concepto;

    /**
     * @ORM\OneToMany(targetEntity="GestionFaenaBundle\Entity\gestionBD\Atributo", mappedBy="articuloAtrConc", cascade={"persist", "remove"})
     * @ORM\OrderBy({"posicion" = "ASC", "id" = "ASC"}) 
     */
    private $atributos;

    /**
     * @var boolean
     *
     * @ORM\Column(name="asignado", type="boolean", options={"default":true})
     */
    private $activo = true;
    
    /**
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\ProcesoFaena", inversedBy="automaticos") 
    * @ORM\JoinColumn(name="id_proceso_faena", referencedColumnName="id")
    */      
    private $procesoFaena;

    /**
     * Get id
     *
     * @return int
     */


    public function getFormaCalculoAutomatico()
    {
        $stock = $this->concepto->getProcesoFaena()->existeArticuloDefinidoManejoStock($this->articulo);
        if ($stock)
        {
            foreach ($this->atributos as $atr) {
                if (($atr->getAtributoAbstracto() == $stock->getAtributo()) && ($atr->getActivo()))
                {
                    return $atr->getCalculo();
                }
            }

        }
        return null;
    }

    public function getAtributoMedibleManualActivo(\GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto $atributo) 
    //para un AtributoAbstracto recupera el AtributoMedibleManual correspondiente
    {
        foreach ($this->atributos as $atr) {
            if (($atr->getAtributoAbstracto() === $atributo) && ($atr->getActivo()) && (get_class($atr) == AtributoMedibleManual::class))
            {
                return $atr;
            }
        }
        return null;
    }

    public function existeAtributoActivo(\GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto $atributo) //verifica si se encuentra definido el atributo
    {
        foreach ($this->atributos as $atr) {
            if (($atr->getAtributoAbstracto() === $atributo) && ($atr->getActivo()))
            {
                return true;
            }
        }
        return false;
    }

    public function getVistaEdicion()
    {
        return $this->concepto->getProcesoFaena()." - ".$this->concepto->getConcepto()." - ".$this->articulo."  -  ".$this->concepto->getTipoMovimiento() ;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * Set articulo
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\Articulo $articulo
     *
     * @return ArticuloAtributoConcepto
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
     * Set concepto
     *
     * @param \GestionFaenaBundle\Entity\faena\ConceptoMovimientoProceso $concepto
     *
     * @return ArticuloAtributoConcepto
     */
    public function setConcepto(\GestionFaenaBundle\Entity\faena\ConceptoMovimientoProceso $concepto = null)
    {
        $this->concepto = $concepto;

        return $this;
    }

    /**
     * Get concepto
     *
     * @return \GestionFaenaBundle\Entity\faena\ConceptoMovimientoProceso
     */
    public function getConcepto()
    {
        return $this->concepto;
    }


    public function __toString()
    {
        return $this->articulo."";
    }

    public function updatePropiedades()
    {
        foreach ($this->atributos as $atr) {
            $atr->setArticuloAtrConc($this);
        }
    }

    /**
     * @ORM\PrePersist
     */
    public function updateAtributos()
    {
        foreach ($this->atributos as $atr) {
            $atr->setAsignado(true);
        }
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     *
     * @return ArticuloAtributoConcepto
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Get activo
     *
     * @return boolean
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * Add atributo
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\Atributo $atributo
     *
     * @return ArticuloAtributoConcepto
     */
    public function addAtributo(\GestionFaenaBundle\Entity\gestionBD\Atributo $atributo)
    {
        $this->atributos[] = $atributo;

        return $this;
    }

    /**
     * Remove atributo
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\Atributo $atributo
     */
    public function removeAtributo(\GestionFaenaBundle\Entity\gestionBD\Atributo $atributo)
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
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->atributos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set procesoFaena
     *
     * @param \GestionFaenaBundle\Entity\ProcesoFaena $procesoFaena
     *
     * @return ArticuloAtributoConcepto
     */
    public function setProcesoFaena(\GestionFaenaBundle\Entity\ProcesoFaena $procesoFaena = null)
    {
        $this->procesoFaena = $procesoFaena;

        return $this;
    }

    /**
     * Get procesoFaena
     *
     * @return \GestionFaenaBundle\Entity\ProcesoFaena
     */
    public function getProcesoFaena()
    {
        return $this->procesoFaena;
    }
}
