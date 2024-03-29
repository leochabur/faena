<?php

namespace GestionFaenaBundle\Entity\faena;

use Doctrine\ORM\Mapping as ORM;
use Psr\Log\LoggerInterface;

/**
 * ComprobanteVenta
 *
 * @ORM\Table(name="sp_st_cbte_vta_st")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\faena\ComprobanteVentaRepository")
 */
class ComprobanteVenta extends MovimientoStock
{
    /**
     * @var date
     *
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;

    /**
     *
     * @ORM\Column(type="integer", name="numero", nullable=true)
     */
    private $numero;

    /**
     * @var bool
     *
     * @ORM\Column(name="confirmado", type="boolean")
     */
    private $confirmado = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="finalizado", type="boolean")
     */
    private $finalizado = false;

    /**
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\gestionBD\EntidadExterna") 
    * @ORM\JoinColumn(name="id_ent_ext", referencedColumnName="id")
    */   
    private $entidad;

    /**
     * @ORM\OneToMany(targetEntity="ItemCarga", mappedBy="comprobante", cascade={"persist", "remove"})
     */
    private $items;

    /**
     *
     * @ORM\Column(name="comentario", type="string", nullable=true)
     */
    private $comentario;
    
    /**
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User") 
    * @ORM\JoinColumn(name="id_usr_confirm", referencedColumnName="id", nullable=true)
    */      
    private $userConfirm;

    /**
     * @ORM\Column(name="fechaConfirm", type="datetime", nullable=true)
    */      
    private $fechaConfirmacion;


    /**
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User") 
    * @ORM\JoinColumn(name="id_usr_fin", referencedColumnName="id", nullable=true)
    */      
    private $userFinalizacion;

    /**
     * @ORM\Column(name="fechaFin", type="datetime", nullable=true)
    */      
    private $fechaFinalizacion;

    /**
    * @ORM\ManyToOne(targetEntity="OrdenCarga", inversedBy="comprobantes") 
    * @ORM\JoinColumn(name="id_ord_cga", referencedColumnName="id", nullable=true)
    */      
    private $ordenCarga;

    /**
    * @ORM\ManyToOne(targetEntity="HorarioCarga") 
    * @ORM\JoinColumn(name="id_hr_cga", referencedColumnName="id", nullable=true)
    */      
    private $horarioCarga;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
    }


    public function getItemOficial()
    {
        return $this->getDetalleItems(true);
    }

    public function getItemNoOficial()
    {
        return $this->getDetalleItems(false);
    }

    private function getDetalleItems($tipo)
    {
        $detalle = 0;
        foreach ($this->items as $it)
        {
            $tv = $it->getTipoVenta();
            if ($tipo)
            {
                if ($tv->getOficial())
                {
                    $detalle++;

                }
            }
            else
            {
                if (!$it->getTipoVenta()->getOficial())
                {
                    $detalle++;
                }
            }
        }
        return $detalle;
    }

    public function getItemConTipoYArticulo(TipoVenta $tipo, \GestionFaenaBundle\Entity\gestionBD\Articulo $articulo)
    {
        foreach ($this->items as $it)
        {
            if (($it->getArticulo() == $articulo) && ($it->getTipoVenta() == $tipo))
            {
                return $it;
            }
        }
        return null;
    }

    protected function updateVisible()
    {
        $this->setVisible(false);
    }

    public function updateValues($promedio, $entityManager, $automatico = false)
    {

    }

    public function getType()
    {
        
    }

    /**
     * Set confirmado
     *
     * @param boolean $confirmado
     *
     * @return ComprobanteVenta
     */
    public function setConfirmado($confirmado)
    {
        $this->confirmado = $confirmado;

        return $this;
    }

    /**
     * Get confirmado
     *
     * @return bool
     */
    public function getConfirmado()
    {
        return $this->confirmado;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return ComprobanteVenta
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set entidad
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\EntidadExterna $entidad
     *
     * @return ComprobanteVenta
     */
    public function setEntidad(\GestionFaenaBundle\Entity\gestionBD\EntidadExterna $entidad = null)
    {
        $this->entidad = $entidad;

        return $this;
    }

    /**
     * Get entidad
     *
     * @return \GestionFaenaBundle\Entity\gestionBD\EntidadExterna
     */
    public function getEntidad()
    {
        return $this->entidad;
    }

    /**
     * Add item
     *
     * @param \GestionFaenaBundle\Entity\faena\ItemCarga $item
     *
     * @return ComprobanteVenta
     */
    public function addItem(\GestionFaenaBundle\Entity\faena\ItemCarga $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove item
     *
     * @param \GestionFaenaBundle\Entity\faena\ItemCarga $item
     */
    public function removeItem(\GestionFaenaBundle\Entity\faena\ItemCarga $item)
    {
        $this->items->removeElement($item);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Set comentario
     *
     * @param string $comentario
     *
     * @return ComprobanteVenta
     */
    public function setComentario($comentario)
    {
        $this->comentario = $comentario;

        return $this;
    }

    /**
     * Get comentario
     *
     * @return string
     */
    public function getComentario()
    {
        return $this->comentario;
    }

    /**
     * Set finalizado
     *
     * @param boolean $finalizado
     *
     * @return ComprobanteVenta
     */
    public function setFinalizado($finalizado)
    {
        $this->finalizado = $finalizado;

        return $this;
    }

    /**
     * Get finalizado
     *
     * @return boolean
     */
    public function getFinalizado()
    {
        return $this->finalizado;
    }

    /**
     * Set userConfirm
     *
     * @param \AppBundle\Entity\User $userConfirm
     *
     * @return ComprobanteVenta
     */
    public function setUserConfirm(\AppBundle\Entity\User $userConfirm = null)
    {
        $this->userConfirm = $userConfirm;

        return $this;
    }

    /**
     * Get userConfirm
     *
     * @return \AppBundle\Entity\User
     */
    public function getUserConfirm()
    {
        return $this->userConfirm;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     *
     * @return ComprobanteVenta
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set ordenCarga
     *
     * @param \GestionFaenaBundle\Entity\faena\OrdenCarga $ordenCarga
     *
     * @return ComprobanteVenta
     */
    public function setOrdenCarga(\GestionFaenaBundle\Entity\faena\OrdenCarga $ordenCarga = null)
    {
        $this->ordenCarga = $ordenCarga;

        return $this;
    }

    /**
     * Get ordenCarga
     *
     * @return \GestionFaenaBundle\Entity\faena\OrdenCarga
     */
    public function getOrdenCarga()
    {
        return $this->ordenCarga;
    }

    /**
     * Set fechaConfirmacion
     *
     * @param \DateTime $fechaConfirmacion
     *
     * @return ComprobanteVenta
     */
    public function setFechaConfirmacion($fechaConfirmacion)
    {
        $this->fechaConfirmacion = $fechaConfirmacion;

        return $this;
    }

    /**
     * Get fechaConfirmacion
     *
     * @return \DateTime
     */
    public function getFechaConfirmacion()
    {
        return $this->fechaConfirmacion;
    }

    /**
     * Set fechaFinalizacion
     *
     * @param \DateTime $fechaFinalizacion
     *
     * @return ComprobanteVenta
     */
    public function setFechaFinalizacion($fechaFinalizacion)
    {
        $this->fechaFinalizacion = $fechaFinalizacion;

        return $this;
    }

    /**
     * Get fechaFinalizacion
     *
     * @return \DateTime
     */
    public function getFechaFinalizacion()
    {
        return $this->fechaFinalizacion;
    }

    /**
     * Set userFinalizacion
     *
     * @param \AppBundle\Entity\User $userFinalizacion
     *
     * @return ComprobanteVenta
     */
    public function setUserFinalizacion(\AppBundle\Entity\User $userFinalizacion = null)
    {
        $this->userFinalizacion = $userFinalizacion;

        return $this;
    }

    /**
     * Get userFinalizacion
     *
     * @return \AppBundle\Entity\User
     */
    public function getUserFinalizacion()
    {
        return $this->userFinalizacion;
    }

    /**
     * Set horarioCarga
     *
     * @param \GestionFaenaBundle\Entity\faena\HorarioCarga $horarioCarga
     *
     * @return ComprobanteVenta
     */
    public function setHorarioCarga(\GestionFaenaBundle\Entity\faena\HorarioCarga $horarioCarga = null)
    {
        $this->horarioCarga = $horarioCarga;

        return $this;
    }

    /**
     * Get horarioCarga
     *
     * @return \GestionFaenaBundle\Entity\faena\HorarioCarga
     */
    public function getHorarioCarga()
    {
        return $this->horarioCarga;
    }
}
