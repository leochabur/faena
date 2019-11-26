<?php

namespace GestionFaenaBundle\Entity\faena;

use Doctrine\ORM\Mapping as ORM;

/**
 * AtributoConcepto
 *
 * @ORM\Table(name="sp_gst_atr_prod_proc_con")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\faena\AtributoConceptoRepository")
 */
class AtributoConcepto
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
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\faena\ConceptoMovimiento", inversedBy="atributos") 
    * @ORM\JoinColumn(name="id_concepto", referencedColumnName="id")
    */      
    private $concepto;

    /**
     * @ORM\ManyToMany(targetEntity="GestionFaenaBundle\Entity\gestionBD\AtributoProceso")
     * @ORM\JoinTable(name="sp_gst_atr_proc_by_conc",
     *      joinColumns={@ORM\JoinColumn(name="id_atr_conc", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_atr_proc", referencedColumnName="id")}
     *      )
     */
    private $atributos;

    /**
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\gestionBD\ArticuloProcesoFaena") 
    * @ORM\JoinColumn(name="id_art_proc_fan", referencedColumnName="id")
    */      
    private $articulo;

    public function existeAtributo(\GestionFaenaBundle\Entity\gestionBD\AtributoProceso $atributo)
    {
        return $this->atributos->contains($atributo);
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
     * Constructor
     */
    public function __construct()
    {
        $this->atributos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set concepto
     *
     * @param \GestionFaenaBundle\Entity\faena\ConceptoMovimiento $concepto
     *
     * @return AtributoConcepto
     */
    public function setConcepto(\GestionFaenaBundle\Entity\faena\ConceptoMovimiento $concepto = null)
    {
        $this->concepto = $concepto;

        return $this;
    }

    /**
     * Get concepto
     *
     * @return \GestionFaenaBundle\Entity\faena\ConceptoMovimiento
     */
    public function getConcepto()
    {
        return $this->concepto;
    }

    /**
     * Add atributo
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\AtributoProceso $atributo
     *
     * @return AtributoConcepto
     */
    public function addAtributo(\GestionFaenaBundle\Entity\gestionBD\AtributoProceso $atributo)
    {
        $this->atributos[] = $atributo;

        return $this;
    }

    /**
     * Remove atributo
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\AtributoProceso $atributo
     */
    public function removeAtributo(\GestionFaenaBundle\Entity\gestionBD\AtributoProceso $atributo)
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
     * Set articulo
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\ArticuloProcesoFaena $articulo
     *
     * @return AtributoConcepto
     */
    public function setArticulo(\GestionFaenaBundle\Entity\gestionBD\ArticuloProcesoFaena $articulo = null)
    {
        $this->articulo = $articulo;

        return $this;
    }

    /**
     * Get articulo
     *
     * @return \GestionFaenaBundle\Entity\gestionBD\ArticuloProcesoFaena
     */
    public function getArticulo()
    {
        return $this->articulo;
    }
}
