<?php

namespace GestionFaenaBundle\Entity\faena;

use Doctrine\ORM\Mapping as ORM;

/**
 * AtributoPorArticuloPorProceso
 *
 * @ORM\Table(name="sp_gst_atr_x_art_x_proc")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\faena\AtributoPorArticuloPorProcesoRepository")
 */
class AtributoPorArticuloPorProceso
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
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\gestionBD\Articulo") 
    * @ORM\JoinColumn(name="id_articulo", referencedColumnName="id")
    */      
    private $articulo;

    /**
     * @ORM\ManyToMany(targetEntity="GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto")
     * @ORM\JoinTable(name="sp_gst_atr_asig_x_art_x_proc",
     *      joinColumns={@ORM\JoinColumn(name="id_atrxartxproc", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_atributo", referencedColumnName="id")}
     *      )
     */
    private $atributos;

    /**
     * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\ProcesoFaena", inversedBy="atributosArticulo")
     * @ORM\JoinColumn(name="id_proc_fan", referencedColumnName="id")
     */
    private $proceso;
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
     * Set articulo
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\Articulo $articulo
     *
     * @return AtributoPorArticuloPorProceso
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
     * Add atributo
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto $atributo
     *
     * @return AtributoPorArticuloPorProceso
     */
    public function addAtributo(\GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto $atributo)
    {
        $this->atributos[] = $atributo;

        return $this;
    }

    /**
     * Remove atributo
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto $atributo
     */
    public function removeAtributo(\GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto $atributo)
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
     * Set proceso
     *
     * @param \GestionFaenaBundle\Entity\ProcesoFaena $proceso
     *
     * @return AtributoPorArticuloPorProceso
     */
    public function setProceso(\GestionFaenaBundle\Entity\ProcesoFaena $proceso = null)
    {
        $this->proceso = $proceso;

        return $this;
    }

    /**
     * Get proceso
     *
     * @return \GestionFaenaBundle\Entity\ProcesoFaena
     */
    public function getProceso()
    {
        return $this->proceso;
    }
}
