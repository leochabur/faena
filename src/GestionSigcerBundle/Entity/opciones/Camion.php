<?php

namespace GestionSigcerBundle\Entity\opciones;

use Doctrine\ORM\Mapping as ORM;

/**
 * Camion
 *
 * @ORM\Table(name="sig_cmion_rpto")
 * @ORM\Entity(repositoryClass="GestionSigcerBundle\Repository\opciones\CamionRepository")
 */
class Camion
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
     * @ORM\Column(name="titular", type="string", length=255)
     */
    private $titular;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=255)
     */
    private $tipo;

    /**
     * @var string
     *
     * @ORM\Column(name="patente", type="string", length=255)
     */
    private $patente;

    /**
     * @var string
     *
     * @ORM\Column(name="senasa", type="string", length=255)
     */
    private $senasa;

    /**
     * @ORM\OneToOne(targetEntity="Zona", inversedBy="camion")
     * @ORM\JoinColumn(name="id_zona", referencedColumnName="id")
     */
    private $zona;



    public function __toString()
    {
        return strtoupper($this->patente);
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
     * Set titular
     *
     * @param string $titular
     *
     * @return Camion
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
     * Set patente
     *
     * @param string $patente
     *
     * @return Camion
     */
    public function setPatente($patente)
    {
        $this->patente = $patente;

        return $this;
    }

    /**
     * Get patente
     *
     * @return string
     */
    public function getPatente()
    {
        return $this->patente;
    }

    /**
     * Set senasa
     *
     * @param string $senasa
     *
     * @return Camion
     */
    public function setSenasa($senasa)
    {
        $this->senasa = $senasa;

        return $this;
    }

    /**
     * Get senasa
     *
     * @return string
     */
    public function getSenasa()
    {
        return $this->senasa;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     *
     * @return Camion
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set zona
     *
     * @param \GestionSigcerBundle\Entity\opciones\Zona $zona
     *
     * @return Camion
     */
    public function setZona(\GestionSigcerBundle\Entity\opciones\Zona $zona = null)
    {
        $this->zona = $zona;

        return $this;
    }

    /**
     * Get zona
     *
     * @return \GestionSigcerBundle\Entity\opciones\Zona
     */
    public function getZona()
    {
        return $this->zona;
    }
}
