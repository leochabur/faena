<?php

namespace GestionFaenaBundle\Entity\gestionBD;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Transportista
 *
 * @ORM\Table(name="sp_gst_ent_ext_trs")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\gestionBD\TransportistaRepository")
 */
class Transportista extends EntidadExterna
{

    /**
     * @var string
     *
     * @ORM\Column(name="cuit", type="string", length=255)
     * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
     */
    private $cuit;

    /**
     * @ORM\Column(name="chofer", type="string", length=255)
     * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
     */
    private $chofer;

    /**
     * @ORM\Column(name="titular", type="string", length=255)
     * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
     */
    private $titular;

    /**
     * @ORM\Column(name="chasis", type="string", length=255)
     * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
     */
    private $chasis;

    /**
     * @ORM\Column(name="acoplado", type="string", length=255)
     * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
     */
    private $acoplado;

    /**
     * @return Transportista
     */
    public function setCuit($cuit)
    {
        $this->cuit = $cuit;

        return $this;
    }

    /**
     * Get cuit
     *
     * @return string
     */
    public function getCuit()
    {
        return $this->cuit;
    }

    /**
     * Set chofer
     *
     * @param string $chofer
     *
     * @return Transportista
     */
    public function setChofer($chofer)
    {
        $this->chofer = $chofer;

        return $this;
    }

    /**
     * Get chofer
     *
     * @return string
     */
    public function getChofer()
    {
        return $this->chofer;
    }

    /**
     * Set titular
     *
     * @param string $titular
     *
     * @return Transportista
     */
    public function setTitular($titular)
    {
        $this->titular = $titular;

        return $this;
    }

    /**
     * Get titular
     *
     * @return string
     */
    public function getTitular()
    {
        return $this->titular;
    }

    /**
     * Set chasis
     *
     * @param string $chasis
     *
     * @return Transportista
     */
    public function setChasis($chasis)
    {
        $this->chasis = $chasis;

        return $this;
    }

    /**
     * Get chasis
     *
     * @return string
     */
    public function getChasis()
    {
        return $this->chasis;
    }

    /**
     * Set acoplado
     *
     * @param string $acoplado
     *
     * @return Transportista
     */
    public function setAcoplado($acoplado)
    {
        $this->acoplado = $acoplado;

        return $this;
    }

    /**
     * Get acoplado
     *
     * @return string
     */
    public function getAcoplado()
    {
        return $this->acoplado;
    }
}
