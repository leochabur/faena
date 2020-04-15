<?php

namespace GestionSigcerBundle\Entity\opciones;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zona
 *
 * @ORM\Table(name="sig_zna_repto")
 * @ORM\Entity(repositoryClass="GestionSigcerBundle\Repository\opciones\ZonaRepository")
 */
class Zona
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
     * @ORM\Column(name="zona", type="string", length=255)
     */
    private $zona;


    public function __toString()
    {
        return $this->zona;
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
     * Set zona
     *
     * @param string $zona
     *
     * @return Zona
     */
    public function setZona($zona)
    {
        $this->zona = $zona;

        return $this;
    }

    /**
     * Get zona
     *
     * @return string
     */
    public function getZona()
    {
        return $this->zona;
    }
}

