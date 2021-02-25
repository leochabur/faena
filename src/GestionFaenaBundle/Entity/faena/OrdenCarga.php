<?php

namespace GestionFaenaBundle\Entity\faena;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrdenCarga
 *
 * @ORM\Table(name="sp_st_ord_cga")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\faena\OrdenCargaRepository")
 */
class OrdenCarga extends MovimientoStock
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @ORM\OneToMany(targetEntity="ComprobanteVenta", mappedBy="ordenCarga")
     * @ORM\OrderBy({"numero" = "ASC"})
     */
    private $comprobantes;

    /**
     * @ORM\ManyToMany(targetEntity="GestionFaenaBundle\Entity\gestionBD\EntidadExterna")
     * @ORM\JoinTable(name="sp_st_snt_ext_for_cbte",
     *      joinColumns={@ORM\JoinColumn(name="id_ord_cga", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_ent_ext", referencedColumnName="id")}
     *      )
     */
    private $entidades;

    /**
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\gestionBD\RubroEntidad") 
    * @ORM\JoinColumn(name="id_rub_ent", referencedColumnName="id", nullable=true)
    */   
    private $rubro;

    /**
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\gestionBD\Transportista") 
    * @ORM\JoinColumn(name="id_transpt", referencedColumnName="id", nullable=true)
    */   
    private $transportista;

    /**
     * @var bool
     *
     * @ORM\Column(name="impresa", type="boolean")
     */
    private $impresa = false;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->entidades = new \Doctrine\Common\Collections\ArrayCollection();
        $this->comprobantes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return OrdenCarga
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

    public function existeComprobanteCargado(\GestionFaenaBundle\Entity\faena\ComprobanteVenta $comprobante)
    {
        foreach ($this->comprobantes as $comp)
        {
            if ((!$comp->getEliminado()) && ($comp->getId() == $comprobante->getId()))
            {
                return true;
            }
        }
        return false;
    }

    /**
     * Set impresa
     *
     * @param boolean $impresa
     *
     * @return OrdenCarga
     */
    public function setImpresa($impresa)
    {
        $this->impresa = $impresa;

        return $this;
    }

    /**
     * Get impresa
     *
     * @return boolean
     */
    public function getImpresa()
    {
        return $this->impresa;
    }

    /**
     * Add comprobante
     *
     * @param \GestionFaenaBundle\Entity\faena\ComprobanteVenta $comprobante
     *
     * @return OrdenCarga
     */
    public function addComprobante(\GestionFaenaBundle\Entity\faena\ComprobanteVenta $comprobante)
    {
        $this->comprobantes[] = $comprobante;

        return $this;
    }

    /**
     * Remove comprobante
     *
     * @param \GestionFaenaBundle\Entity\faena\ComprobanteVenta $comprobante
     */
    public function removeComprobante(\GestionFaenaBundle\Entity\faena\ComprobanteVenta $comprobante)
    {
        $this->comprobantes->removeElement($comprobante);
    }

    /**
     * Get comprobantes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComprobantes()
    {
        return $this->comprobantes;
    }

    /**
     * Set rubro
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\RubroEntidad $rubro
     *
     * @return OrdenCarga
     */
    public function setRubro(\GestionFaenaBundle\Entity\gestionBD\RubroEntidad $rubro = null)
    {
        $this->rubro = $rubro;

        return $this;
    }

    /**
     * Get rubro
     *
     * @return \GestionFaenaBundle\Entity\gestionBD\RubroEntidad
     */
    public function getRubro()
    {
        return $this->rubro;
    }

    /**
     * Add entidade
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\EntidadExterna $entidade
     *
     * @return OrdenCarga
     */
    public function addEntidade(\GestionFaenaBundle\Entity\gestionBD\EntidadExterna $entidade)
    {
        $this->entidades[] = $entidade;

        return $this;
    }

    /**
     * Remove entidade
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\EntidadExterna $entidade
     */
    public function removeEntidade(\GestionFaenaBundle\Entity\gestionBD\EntidadExterna $entidade)
    {
        $this->entidades->removeElement($entidade);
    }

    /**
     * Get entidades
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEntidades()
    {
        return $this->entidades;
    }

    /**
     * Set transportista
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\Transportista $transportista
     *
     * @return OrdenCarga
     */
    public function setTransportista(\GestionFaenaBundle\Entity\gestionBD\Transportista $transportista = null)
    {
        $this->transportista = $transportista;

        return $this;
    }

    /**
     * Get transportista
     *
     * @return \GestionFaenaBundle\Entity\gestionBD\Transportista
     */
    public function getTransportista()
    {
        return $this->transportista;
    }

    public function getTitularTransporte()
    {
        if ($this->transportista)
        {
            return $this->transportista->getTitular();
        }
        return ;
    }
}
