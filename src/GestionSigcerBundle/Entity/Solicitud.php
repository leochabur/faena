<?php

namespace GestionSigcerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Solicitud
 *
 * @ORM\Table(name="solicitud")
 * @ORM\Entity(repositoryClass="GestionSigcerBundle\Repository\SolicitudRepository")
 */
class Solicitud
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
     * @ORM\Column(name="lugarDestino", type="string", length=255)
     */
    private $lugarDestino;

    /**
     * @var string
     *
     * @ORM\Column(name="precintoAduana", type="string", length=255)
     */
    private $precintoAduana;

    /**
     * @var string
     *
     * @ORM\Column(name="precintoSenasa", type="string", length=255)
     */
    private $precintoSenasa;

    /**
     * @var string
     *
     * @ORM\Column(name="observaciones", type="text")
     */
    private $observaciones;

    /**
     * @var string
     *
     * @ORM\Column(name="remitoNumero", type="string", length=255)
     */
    private $remitoNumero;

    /**
     * @var float
     *
     * @ORM\Column(name="temperatura", type="float")
     */
    private $temperatura;

    /**
     * @var float
     *
     * @ORM\Column(name="termoTemperatura", type="float")
     */
    private $termoTemperatura;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="termoTiempo", type="time")
     */
    private $termoTiempo;


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
     * Set lugarDestino
     *
     * @param string $lugarDestino
     *
     * @return Solicitud
     */
    public function setLugarDestino($lugarDestino)
    {
        $this->lugarDestino = $lugarDestino;

        return $this;
    }

    /**
     * Get lugarDestino
     *
     * @return string
     */
    public function getLugarDestino()
    {
        return $this->lugarDestino;
    }

    /**
     * Set precintoAduana
     *
     * @param string $precintoAduana
     *
     * @return Solicitud
     */
    public function setPrecintoAduana($precintoAduana)
    {
        $this->precintoAduana = $precintoAduana;

        return $this;
    }

    /**
     * Get precintoAduana
     *
     * @return string
     */
    public function getPrecintoAduana()
    {
        return $this->precintoAduana;
    }

    /**
     * Set precintoSenasa
     *
     * @param string $precintoSenasa
     *
     * @return Solicitud
     */
    public function setPrecintoSenasa($precintoSenasa)
    {
        $this->precintoSenasa = $precintoSenasa;

        return $this;
    }

    /**
     * Get precintoSenasa
     *
     * @return string
     */
    public function getPrecintoSenasa()
    {
        return $this->precintoSenasa;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     *
     * @return Solicitud
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * Get observaciones
     *
     * @return string
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Set remitoNumero
     *
     * @param string $remitoNumero
     *
     * @return Solicitud
     */
    public function setRemitoNumero($remitoNumero)
    {
        $this->remitoNumero = $remitoNumero;

        return $this;
    }

    /**
     * Get remitoNumero
     *
     * @return string
     */
    public function getRemitoNumero()
    {
        return $this->remitoNumero;
    }

    /**
     * Set temperatura
     *
     * @param float $temperatura
     *
     * @return Solicitud
     */
    public function setTemperatura($temperatura)
    {
        $this->temperatura = $temperatura;

        return $this;
    }

    /**
     * Get temperatura
     *
     * @return float
     */
    public function getTemperatura()
    {
        return $this->temperatura;
    }

    /**
     * Set termoTemperatura
     *
     * @param float $termoTemperatura
     *
     * @return Solicitud
     */
    public function setTermoTemperatura($termoTemperatura)
    {
        $this->termoTemperatura = $termoTemperatura;

        return $this;
    }

    /**
     * Get termoTemperatura
     *
     * @return float
     */
    public function getTermoTemperatura()
    {
        return $this->termoTemperatura;
    }

    /**
     * Set termoTiempo
     *
     * @param \DateTime $termoTiempo
     *
     * @return Solicitud
     */
    public function setTermoTiempo($termoTiempo)
    {
        $this->termoTiempo = $termoTiempo;

        return $this;
    }

    /**
     * Get termoTiempo
     *
     * @return \DateTime
     */
    public function getTermoTiempo()
    {
        return $this->termoTiempo;
    }
}

