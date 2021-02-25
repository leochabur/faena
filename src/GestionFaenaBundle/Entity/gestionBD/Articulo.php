<?php

namespace GestionFaenaBundle\Entity\gestionBD;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Articulo
 *
 * @ORM\Table(name="sp_gst_art")
 * @UniqueEntity("nombre", message="Articulo existente en la base de datos")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\gestionBD\ArticuloRepository")
 */
class Articulo
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
     * @ORM\Column(name="codigoInterno", type="string", length=255, nullable=true)
     */
    private $codigoInterno;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="nombreResumido", type="string", length=255, nullable=true)
     */
    private $nombreResumido;

    /**
     * @var string
     *
     * @ORM\Column(name="codigoSenasa", type="string", length=255, nullable=true)
     */
    private $codigoSenasa;

    /**
     * @var string
     *
     * @ORM\Column(name="nombreSenasa", type="string", length=255, nullable=true)
     */
    private $nombreSenasa;

    /**
     * @ORM\OneToMany(targetEntity="ArticuloAtributoConcepto", mappedBy="articulo")
     */
    private $artsAtrConc;

    /**
     * @ORM\Column(name="presentacionKg", type="float", nullable=true)
     */
    private $presentacionKg;

    /**
     * @ORM\Column(name="presentacionUnidad", type="integer", nullable=true)
     */
    private $presentacionUnidad;

    /**
    * @ORM\ManyToOne(targetEntity="CategoriaArticulo") 
    * @ORM\JoinColumn(name="id_categ", referencedColumnName="id")
    */      
    private $categoria;

    /**
    * @ORM\ManyToOne(targetEntity="SubcategoriaArticulo") 
    * @ORM\JoinColumn(name="id_subcateg", referencedColumnName="id")
    */      
    private $subcategoria;

    /**
     * @ORM\Column(name="eliminado", type="boolean", options={"default":false})
     */
    private $eliminado = false;

    /**
     * @ORM\Column(name="orden", type="float", nullable=true)
     */
    private $orden;
    
    /**
     * @ORM\Column(name="clasificable", type="boolean", options={"default":false})
     */
    private $clasificable = false; //indica si el articulo se debe clasificar en otros (Ej. Cajon de Pollo al ingresar a camara se clasifina en PP etc. se generara una transformacion 1 a 1 entre el Cajon de Pollo abstracto y la clasificacion final x ej PP Sin Bandeja Calibre 7)

    /**
     * @ORM\OneToMany(targetEntity="Articulo", mappedBy="articuloBase")
     */
    private $articulosClasificables;

    /**
     * @ORM\ManyToOne(targetEntity="Articulo", inversedBy="articulosClasificables")
     * @ORM\JoinColumn(name="id_articulo", referencedColumnName="id", nullable=true)
     */
    private $articuloBase;

    /**
     * @var string
     *
     * @ORM\Column(name="descVenta", type="string", length=255, nullable=true)
     */
    private $descVenta;


    public function getNombreVenta()
    {
        return ($this->descVenta?$this->descVenta:$this->nombre);
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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Articulo
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    public function __toString()
    {
        return $this->nombre;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->artsAtrConc = new \Doctrine\Common\Collections\ArrayCollection();
        $this->articulosClasificables = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add artsAtrConc
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\ArticuloAtributoConcepto $artsAtrConc
     *
     * @return Articulo
     */
    public function addArtsAtrConc(\GestionFaenaBundle\Entity\gestionBD\ArticuloAtributoConcepto $artsAtrConc)
    {
        $this->artsAtrConc[] = $artsAtrConc;

        return $this;
    }

    /**
     * Remove artsAtrConc
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\ArticuloAtributoConcepto $artsAtrConc
     */
    public function removeArtsAtrConc(\GestionFaenaBundle\Entity\gestionBD\ArticuloAtributoConcepto $artsAtrConc)
    {
        $this->artsAtrConc->removeElement($artsAtrConc);
    }

    /**
     * Get artsAtrConc
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArtsAtrConc()
    {
        return $this->artsAtrConc;
    }

    /**
     * Set codigoSenasa
     *
     * @param string $codigoSenasa
     *
     * @return Articulo
     */
    public function setCodigoSenasa($codigoSenasa)
    {
        $this->codigoSenasa = $codigoSenasa;

        return $this;
    }

    /**
     * Get codigoSenasa
     *
     * @return string
     */
    public function getCodigoSenasa()
    {
        return $this->codigoSenasa;
    }

    /**
     * Set nombreSenasa
     *
     * @param string $nombreSenasa
     *
     * @return Articulo
     */
    public function setNombreSenasa($nombreSenasa)
    {
        $this->nombreSenasa = $nombreSenasa;

        return $this;
    }

    /**
     * Get nombreSenasa
     *
     * @return string
     */
    public function getNombreSenasa()
    {
        return $this->nombreSenasa;
    }

    /**
     * Set codigoInterno
     *
     * @param string $codigoInterno
     *
     * @return Articulo
     */
    public function setCodigoInterno($codigoInterno)
    {
        $this->codigoInterno = $codigoInterno;

        return $this;
    }

    /**
     * Get codigoInterno
     *
     * @return string
     */
    public function getCodigoInterno()
    {
        return $this->codigoInterno;
    }

    /**
     * Set presentacionKg
     *
     * @param float $presentacionKg
     *
     * @return Articulo
     */
    public function setPresentacionKg($presentacionKg)
    {
        $this->presentacionKg = $presentacionKg;

        return $this;
    }

    /**
     * Get presentacionKg
     *
     * @return float
     */
    public function getPresentacionKg()
    {
        return $this->presentacionKg;
    }

    /**
     * Set presentacionUnidad
     *
     * @param integer $presentacionUnidad
     *
     * @return Articulo
     */
    public function setPresentacionUnidad($presentacionUnidad)
    {
        $this->presentacionUnidad = $presentacionUnidad;

        return $this;
    }

    /**
     * Get presentacionUnidad
     *
     * @return integer
     */
    public function getPresentacionUnidad()
    {
        return $this->presentacionUnidad;
    }


    /**
     * Set categoria
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\CategoriaArticulo $categoria
     *
     * @return Articulo
     */
    public function setCategoria(\GestionFaenaBundle\Entity\gestionBD\CategoriaArticulo $categoria = null)
    {
        $this->categoria = $categoria;

        return $this;
    }

    /**
     * Get categoria
     *
     * @return \GestionFaenaBundle\Entity\gestionBD\CategoriaArticulo
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * Set subcategoria
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\SubcategoriaArticulo $subcategoria
     *
     * @return Articulo
     */
    public function setSubcategoria(\GestionFaenaBundle\Entity\gestionBD\SubcategoriaArticulo $subcategoria = null)
    {
        $this->subcategoria = $subcategoria;

        return $this;
    }

    /**
     * Get subcategoria
     *
     * @return \GestionFaenaBundle\Entity\gestionBD\SubcategoriaArticulo
     */
    public function getSubcategoria()
    {
        return $this->subcategoria;
    }

    /**
     * Set nombreResumido
     *
     * @param string $nombreResumido
     *
     * @return Articulo
     */
    public function setNombreResumido($nombreResumido)
    {
        $this->nombreResumido = $nombreResumido;

        return $this;
    }

    /**
     * Get nombreResumido
     *
     * @return string
     */
    public function getNombreResumido()
    {
        return $this->nombreResumido;
    }

    /**
     * Set eliminado
     *
     * @param boolean $eliminado
     *
     * @return Articulo
     */
    public function setEliminado($eliminado)
    {
        $this->eliminado = $eliminado;

        return $this;
    }

    /**
     * Get eliminado
     *
     * @return boolean
     */
    public function getEliminado()
    {
        return $this->eliminado;
    }

    /**
     * Set orden
     *
     * @param float $orden
     *
     * @return Articulo
     */
    public function setOrden($orden)
    {
        $this->orden = $orden;

        return $this;
    }

    /**
     * Get orden
     *
     * @return float
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * Set clasificable
     *
     * @param boolean $clasificable
     *
     * @return Articulo
     */
    public function setClasificable($clasificable)
    {
        $this->clasificable = $clasificable;

        return $this;
    }

    /**
     * Get clasificable
     *
     * @return boolean
     */
    public function getClasificable()
    {
        return $this->clasificable;
    }

    /**
     * Add articulosClasificable
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\Articulo $articulosClasificable
     *
     * @return Articulo
     */
    public function addArticulosClasificable(\GestionFaenaBundle\Entity\gestionBD\Articulo $articulosClasificable)
    {
        $this->articulosClasificables[] = $articulosClasificable;

        return $this;
    }

    /**
     * Remove articulosClasificable
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\Articulo $articulosClasificable
     */
    public function removeArticulosClasificable(\GestionFaenaBundle\Entity\gestionBD\Articulo $articulosClasificable)
    {
        $this->articulosClasificables->removeElement($articulosClasificable);
    }

    /**
     * Get articulosClasificables
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArticulosClasificables()
    {
        return $this->articulosClasificables;
    }

    /**
     * Set articuloBase
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\Articulo $articuloBase
     *
     * @return Articulo
     */
    public function setArticuloBase(\GestionFaenaBundle\Entity\gestionBD\Articulo $articuloBase = null)
    {
        $this->articuloBase = $articuloBase;

        return $this;
    }

    /**
     * Get articuloBase
     *
     * @return \GestionFaenaBundle\Entity\gestionBD\Articulo
     */
    public function getArticuloBase()
    {
        return $this->articuloBase;
    }

    /**
     * Set descVenta
     *
     * @param string $descVenta
     *
     * @return Articulo
     */
    public function setDescVenta($descVenta)
    {
        $this->descVenta = $descVenta;

        return $this;
    }

    /**
     * Get descVenta
     *
     * @return string
     */
    public function getDescVenta()
    {
        return $this->descVenta;
    }
}
