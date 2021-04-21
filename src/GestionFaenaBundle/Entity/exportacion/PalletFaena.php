<?php

namespace GestionFaenaBundle\Entity\exportacion;

use Doctrine\ORM\Mapping as ORM;

/**
 * PalletFaena
 *
 * @ORM\Table(name="sp_st_pallet_faena")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\exportacion\PalletFaenaRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class PalletFaena
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
     * @ORM\Column(name="codigo", type="string", length=255)
     */
    private $codigo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaCreacion", type="datetime")
     */
    private $fechaCreacion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaFinalizacion", type="datetime", nullable=true)
     */
    private $fechaFinalizacion;

    /**
     * @var bool
     *
     * @ORM\Column(name="eliminado", type="boolean")
     */
    private $eliminado = false;

    /**
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\exportacion\TipoPallet")
    * @ORM\JoinColumn(name="id_tipo_pallet", referencedColumnName="id")
    */
    private $tipoPallet;

    /**
     * @ORM\ManyToMany(targetEntity="GestionFaenaBundle\Entity\faena\ValorAtributo")
     * @ORM\JoinTable(name="sp_st_valores_for_pallet_faena",
     *      joinColumns={@ORM\JoinColumn(name="id_pallet", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_valor", referencedColumnName="id")}
     *      )
     */
    private $valores;

    /**
     * @ORM\Column(name="completo", type="boolean", nullable=false, options={"default":false})
     */
    private $completo = false;


    /**
     * @ORM\Column(name="acumulado", type="integer", options={"default": 0})
     */
    private $acumulado = 0;


    public function __toString()
    {
        return strtoupper($this->codigo);
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
     * Set codigo
     *
     * @param string $codigo
     *
     * @return PalletFaena
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return PalletFaena
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    /**
     * Set fechaFinalizacion
     *
     * @param \DateTime $fechaFinalizacion
     *
     * @return PalletFaena
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
     * Set eliminado
     *
     * @param boolean $eliminado
     *
     * @return PalletFaena
     */
    public function setEliminado($eliminado)
    {
        $this->eliminado = $eliminado;

        return $this;
    }

    /**
     * Get eliminado
     *
     * @return bool
     */
    public function getEliminado()
    {
        return $this->eliminado;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->valores = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set tipoPallet
     *
     * @param \GestionFaenaBundle\Entity\exportacion\TipoPallet $tipoPallet
     *
     * @return PalletFaena
     */
    public function setTipoPallet(\GestionFaenaBundle\Entity\exportacion\TipoPallet $tipoPallet = null)
    {
        $this->tipoPallet = $tipoPallet;

        return $this;
    }

    /**
     * Get tipoPallet
     *
     * @return \GestionFaenaBundle\Entity\exportacion\TipoPallet
     */
    public function getTipoPallet()
    {
        return $this->tipoPallet;
    }

    /**
     * Add valore
     *
     * @param \GestionFaenaBundle\Entity\faena\ValorAtributo $valore
     *
     * @return PalletFaena
     */
    public function addValore(\GestionFaenaBundle\Entity\faena\ValorAtributo $valore)
    {
        $this->valores[] = $valore;

        return $this;
    }

    /**
     * Remove valore
     *
     * @param \GestionFaenaBundle\Entity\faena\ValorAtributo $valore
     */
    public function removeValore(\GestionFaenaBundle\Entity\faena\ValorAtributo $valore)
    {
        $this->valores->removeElement($valore);
    }

    /**
     * Get valores
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getValores()
    {
        return $this->valores;
    }

    /**
     * Set completo
     *
     * @param boolean $completo
     *
     * @return PalletFaena
     */
    public function setCompleto($completo)
    {
        $this->completo = $completo;

        return $this;
    }

    /**
     * Get completo
     *
     * @return boolean
     */
    public function getCompleto()
    {
        return $this->completo;
    }

    /**
     * Set acumulado
     *
     * @param integer $acumulado
     *
     * @return PalletFaena
     */
    public function setAcumulado($acumulado)
    {
        $this->acumulado = $acumulado;

        return $this;
    }

    /**
     * Get acumulado
     *
     * @return integer
     */
    public function getAcumulado()
    {
        return $this->acumulado;
    }

    /**
     * @ORM\PreUpdate
     */
    public function actualizarPallet()
    {
        $count = 0;
        foreach ($this->valores as $val)
        {
            $count+= $val->getData(false);
        }
        $this->acumulado = $count;

        if ($this->acumulado == $this->tipoPallet->getCapacidad())
        {
            $this->completo = true;
            $this->fechaFinalizacion = new \DateTime();
        }
    }
}
