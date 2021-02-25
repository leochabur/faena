<?php

namespace GestionFaenaBundle\Entity\gestionBD;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Granja
 *
 * @ORM\Table(name="sp_gst_ent_ext_gja")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\gestionBD\GranjaRepository")
 */
class Granja extends EntidadExterna
{

    /**
     * @var bool
     *
     * @ORM\Column(name="propia", type="boolean")
     */
    private $propia = false;


    /**
     * @var  string
     *
     * @ORM\Column(name="renspa", type="string")
     * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
     */
    private $renspa;

    /**
    * @ORM\ManyToOne(targetEntity="Ciudad") 
    * @ORM\JoinColumn(name="id_city", referencedColumnName="id")
     * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
    */      
    private $ciudad;


    protected function getIsCliente()
    {
        return false;
    }

    /**
     * Set propia
     *
     * @param boolean $propia
     *
     * @return Granja
     */
    public function setPropia($propia)
    {
        $this->propia = $propia;

        return $this;
    }

    /**
     * Get propia
     *
     * @return bool
     */
    public function getPropia()
    {
        return $this->propia;
    }

    /**
     * Set renspa
     *
     * @param string $renspa
     *
     * @return Granja
     */
    public function setRenspa($renspa)
    {
        $this->renspa = $renspa;

        return $this;
    }

    /**
     * Get renspa
     *
     * @return string
     */
    public function getRenspa()
    {
        return $this->renspa;
    }

    /**
     * Set ciudad
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\Ciudad $ciudad
     *
     * @return Granja
     */
    public function setCiudad(\GestionFaenaBundle\Entity\gestionBD\Ciudad $ciudad = null)
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    /**
     * Get ciudad
     *
     * @return \GestionFaenaBundle\Entity\gestionBD\Ciudad
     */
    public function getCiudad()
    {
        return $this->ciudad;
    }
}
