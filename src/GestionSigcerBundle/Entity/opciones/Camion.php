<?php

namespace GestionSigcerBundle\Entity\opciones;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Camion
 *
 * @ORM\Table(name="sig_cmion_rpto")
 * @ORM\Entity(repositoryClass="GestionSigcerBundle\Repository\opciones\CamionRepository")
 * @UniqueEntity(
 *     fields={"patente"},
 *     message="Ya existe un camion con el dominio ingresado!!"
 * )
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
     * @ORM\Column(name="tipo", type="string", length=255)
     */
    private $tipo;

    /**
     * @var string
     *
     * @ORM\Column(name="patente", type="string", length=255)
     * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
     */
    private $patente;

    /**
     * @var string
     *
     * @ORM\Column(name="senasa", type="string", length=255)
     * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
     */
    private $senasa;

    /**
     *
     * @ORM\Column(name="precintos", type="integer")
    * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
     */
    private $cantPrecintos;



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
     * Set cantPrecintos
     *
     * @param integer $cantPrecintos
     *
     * @return Camion
     */
    public function setCantPrecintos($cantPrecintos)
    {
        $this->cantPrecintos = $cantPrecintos;

        return $this;
    }

    /**
     * Get cantPrecintos
     *
     * @return integer
     */
    public function getCantPrecintos()
    {
        return $this->cantPrecintos;
    }
}
