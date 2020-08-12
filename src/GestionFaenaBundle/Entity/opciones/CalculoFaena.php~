<?php

namespace GestionFaenaBundle\Entity\opciones;

use Doctrine\ORM\Mapping as ORM;

/**
 * CalculoFaena
 *
 * @ORM\Table(name="sp_def_calc_fan")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\opciones\CalculoFaenaRepository")
 */
class CalculoFaena
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
     * @ORM\Column(name="calculo", type="string", length=255, unique=true)
     */
    private $calculo;

    /**
     * @var string
     *
     * @ORM\Column(name="titulo", type="string", length=255)
     */
    private $titulo;

    /**
     *
     * @ORM\Column(name="nombreArticulo", type="string", length=255, nullable=true)
     */
    private $nombreArticulo;

    /**
     * @ORM\ManyToMany(targetEntity="GestionFaenaBundle\Entity\ProcesoFaena")
     * @ORM\JoinTable(name="sp_proc_for_calc_faena",
     *      joinColumns={@ORM\JoinColumn(name="id_fan_calc", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_proc_fan", referencedColumnName="id")}
     *      )
     */
    private $procesos;

    /**
     * @ORM\ManyToMany(targetEntity="GestionFaenaBundle\Entity\faena\ConceptoMovimiento")
     * @ORM\JoinTable(name="sp_cn_for_calc_fn",
     *      joinColumns={@ORM\JoinColumn(name="id_fan_calc", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_con_mov", referencedColumnName="id")}
     *      )
     */
    private $conceptos;

    /**
     * @ORM\ManyToMany(targetEntity="GestionFaenaBundle\Entity\faena\TipoMovimientoConcepto")
     * @ORM\JoinTable(name="sp_typ_mov_for_calc_fn",
     *      joinColumns={@ORM\JoinColumn(name="id_fan_calc", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_ty_mov", referencedColumnName="id")}
     *      )
     */
    private $tipos;

    /**
     * @ORM\ManyToMany(targetEntity="GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto")
     * @ORM\JoinTable(name="sp_atr_abst_for_calc_fn",
     *      joinColumns={@ORM\JoinColumn(name="id_fan_calc", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_atr_abs", referencedColumnName="id")}
     *      )
     */
    private $atributos;    

    /**
     * @ORM\ManyToMany(targetEntity="GestionFaenaBundle\Entity\gestionBD\Articulo")
     * @ORM\JoinTable(name="sp_art_for_calc_fn",
     *      joinColumns={@ORM\JoinColumn(name="id_fan_calc", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_art", referencedColumnName="id")}
     *      )
     */
    private $articulos;



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
     * Set calculo
     *
     * @param string $calculo
     *
     * @return CalculoFaena
     */
    public function setCalculo($calculo)
    {
        $this->calculo = $calculo;

        return $this;
    }

    /**
     * Get calculo
     *
     * @return string
     */
    public function getCalculo()
    {
        return $this->calculo;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->procesos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->conceptos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tipos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->atributos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->articulos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add proceso
     *
     * @param \GestionFaenaBundle\Entity\ProcesoFaena $proceso
     *
     * @return CalculoFaena
     */
    public function addProceso(\GestionFaenaBundle\Entity\ProcesoFaena $proceso)
    {
        $this->procesos[] = $proceso;

        return $this;
    }

    /**
     * Remove proceso
     *
     * @param \GestionFaenaBundle\Entity\ProcesoFaena $proceso
     */
    public function removeProceso(\GestionFaenaBundle\Entity\ProcesoFaena $proceso)
    {
        $this->procesos->removeElement($proceso);
    }

    /**
     * Get procesos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProcesos()
    {
        return $this->procesos;
    }

    /**
     * Add concepto
     *
     * @param \GestionFaenaBundle\Entity\faena\ConceptoMovimiento $concepto
     *
     * @return CalculoFaena
     */
    public function addConcepto(\GestionFaenaBundle\Entity\faena\ConceptoMovimiento $concepto)
    {
        $this->conceptos[] = $concepto;

        return $this;
    }

    /**
     * Remove concepto
     *
     * @param \GestionFaenaBundle\Entity\faena\ConceptoMovimiento $concepto
     */
    public function removeConcepto(\GestionFaenaBundle\Entity\faena\ConceptoMovimiento $concepto)
    {
        $this->conceptos->removeElement($concepto);
    }

    /**
     * Get conceptos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getConceptos()
    {
        return $this->conceptos;
    }

    /**
     * Add tipo
     *
     * @param \GestionFaenaBundle\Entity\faena\TipoMovimientoConcepto $tipo
     *
     * @return CalculoFaena
     */
    public function addTipo(\GestionFaenaBundle\Entity\faena\TipoMovimientoConcepto $tipo)
    {
        $this->tipos[] = $tipo;

        return $this;
    }

    /**
     * Remove tipo
     *
     * @param \GestionFaenaBundle\Entity\faena\TipoMovimientoConcepto $tipo
     */
    public function removeTipo(\GestionFaenaBundle\Entity\faena\TipoMovimientoConcepto $tipo)
    {
        $this->tipos->removeElement($tipo);
    }

    /**
     * Get tipos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTipos()
    {
        return $this->tipos;
    }

    /**
     * Add atributo
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto $atributo
     *
     * @return CalculoFaena
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
     * Set titulo
     *
     * @param string $titulo
     *
     * @return CalculoFaena
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set nombreArticulo
     *
     * @param string $nombreArticulo
     *
     * @return CalculoFaena
     */
    public function setNombreArticulo($nombreArticulo)
    {
        $this->nombreArticulo = $nombreArticulo;

        return $this;
    }

    /**
     * Get nombreArticulo
     *
     * @return string
     */
    public function getNombreArticulo()
    {
        return $this->nombreArticulo;
    }

    /**
     * Add articulo
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\Articulo $articulo
     *
     * @return CalculoFaena
     */
    public function addArticulo(\GestionFaenaBundle\Entity\gestionBD\Articulo $articulo)
    {
        $this->articulos[] = $articulo;

        return $this;
    }

    /**
     * Remove articulo
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\Articulo $articulo
     */
    public function removeArticulo(\GestionFaenaBundle\Entity\gestionBD\Articulo $articulo)
    {
        $this->articulos->removeElement($articulo);
    }

    /**
     * Get articulos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArticulos()
    {
        return $this->articulos;
    }
}
