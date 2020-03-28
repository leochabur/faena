<?php

namespace GestionFaenaBundle\Entity\faena;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoMovimientoConcepto
 *
 * @ORM\Table(name="sp_gst_tpo_mov_con")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\faena\TipoMovimientoConceptoRepository")
 */
class TipoMovimientoConcepto
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
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var int
     *
     * @ORM\Column(name="instancia", type="integer")
     */
    private $instancia;

    /**
     * @ORM\OneToMany(targetEntity="ConceptoMovimientoProceso", mappedBy="tipoMovimiento")
     */
    private $conceptosMovimientos;

    /**
     * @var int
     *
     * @ORM\Column(name="transforma", type="boolean", options={"default" = false})
     */
    private $transformaProductos; //indica si el tipo de movimiento transforma articulos, dato necesario para indicar que se debe confgiurar cual es el articulo de origen

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
     * @return TipoMovimientoConcepto
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

    /**
     * Set instancia
     *
     * @param integer $instancia
     *
     * @return TipoMovimientoConcepto
     */
    public function setInstancia($instancia)
    {
        $this->instancia = $instancia;

        return $this;
    }

    /**
     * Get instancia
     *
     * @return int
     */
    public function getInstancia()
    {
        return $this->instancia;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->conceptosMovimientos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add conceptosMovimiento
     *
     * @param \GestionFaenaBundle\Entity\faena\ConceptoMovimientoProceso $conceptosMovimiento
     *
     * @return TipoMovimientoConcepto
     */
    public function addConceptosMovimiento(\GestionFaenaBundle\Entity\faena\ConceptoMovimientoProceso $conceptosMovimiento)
    {
        $this->conceptosMovimientos[] = $conceptosMovimiento;

        return $this;
    }

    /**
     * Remove conceptosMovimiento
     *
     * @param \GestionFaenaBundle\Entity\faena\ConceptoMovimientoProceso $conceptosMovimiento
     */
    public function removeConceptosMovimiento(\GestionFaenaBundle\Entity\faena\ConceptoMovimientoProceso $conceptosMovimiento)
    {
        $this->conceptosMovimientos->removeElement($conceptosMovimiento);
    }

    /**
     * Get conceptosMovimientos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getConceptosMovimientos()
    {
        return $this->conceptosMovimientos;
    }

    public function __toString()
    {
        return $this->nombre;
    }

    public function getInstanciaMovimiento()
    {
        switch ($this->instancia) {
            case 2:
                return new EntradaStock();
                break;
            case 3:
                return new SalidaStock();
                break;
            case 4:
                return new TransformarStock();
                break;
            case 5:
                return new TransferirStock();
                break;
            default:
                return;
                break;
        }
    }

    /**
     * Set transformaProductos
     *
     * @param boolean $transformaProductos
     *
     * @return TipoMovimientoConcepto
     */
    public function setTransformaProductos($transformaProductos)
    {
        $this->transformaProductos = $transformaProductos;

        return $this;
    }

    /**
     * Get transformaProductos
     *
     * @return boolean
     */
    public function getTransformaProductos()
    {
        return $this->transformaProductos;
    }
}
