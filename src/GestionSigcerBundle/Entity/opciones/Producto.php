<?php

namespace GestionSigcerBundle\Entity\opciones;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Producto
 *
 * @ORM\Table(name="sig_prdo_det")
 * @ORM\Entity(repositoryClass="GestionSigcerBundle\Repository\opciones\ProductoRepository")
 */
class Producto
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
    * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="codigoCapa", type="string", length=255)
    * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
     */
    private $codigoCapa;

    /**
     * @var string
     *
     * @ORM\Column(name="marca", type="string", length=255)
    * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
     */
    private $marca;


    public function __toString()
    {
        return $this->nombre." - ".$this->codigoCapa;
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
     * @return Producto
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
     * Set codigoCapa
     *
     * @param string $codigoCapa
     *
     * @return Producto
     */
    public function setCodigoCapa($codigoCapa)
    {
        $this->codigoCapa = $codigoCapa;

        return $this;
    }

    /**
     * Get codigoCapa
     *
     * @return string
     */
    public function getCodigoCapa()
    {
        return $this->codigoCapa;
    }

    /**
     * Set marca
     *
     * @param string $marca
     *
     * @return Producto
     */
    public function setMarca($marca)
    {
        $this->marca = $marca;

        return $this;
    }

    /**
     * Get marca
     *
     * @return string
     */
    public function getMarca()
    {
        return $this->marca;
    }
}

