<?php

namespace GestionFaenaBundle\Entity\gestionBD;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategoriaArticulo
 *
 * @ORM\Table(name="sp_gst_cat_art")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\gestionBD\CategoriaArticuloRepository")
 */
class CategoriaArticulo
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
     * @ORM\Column(name="categoria", type="string", length=255)
     */
    private $categoria;

    /**
     * @ORM\Column(name="orden", type="float", nullable=true)
     */
    private $orden;

    public function __toString()
    {
        return $this->categoria;
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
     * Set categoria
     *
     * @param string $categoria
     *
     * @return CategoriaArticulo
     */
    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;

        return $this;
    }

    /**
     * Get categoria
     *
     * @return string
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * Set orden
     *
     * @param float $orden
     *
     * @return CategoriaArticulo
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
}
