<?php

namespace GestionFaenaBundle\Entity\gestionBD;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * RubroEntidad
 *
 * @ORM\Table(name="sp_gst_rub_ent_ext")
 * @UniqueEntity("rubro", message="Grupo de venta existente en la base de datos")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\gestionBD\RubroEntidadRepository")

 */

class RubroEntidad
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
     * @ORM\Column(name="rubro", type="string", length=255, unique=true)
     * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
     */
    private $rubro;

    /**
     * @var bool
     *
     * @ORM\Column(name="activo", type="boolean")
     */
    private $activo = true;

    /**
    *@ORM\OneToMany(targetEntity="EntidadExterna", mappedBy="rubro")
    */
    private $entidades;

    public function __toString()
    {
        return strtoupper($this->rubro);
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
     * Set rubro
     *
     * @param string $rubro
     *
     * @return RubroEntidad
     */
    public function setRubro($rubro)
    {
        $this->rubro = $rubro;

        return $this;
    }

    /**
     * Get rubro
     *
     * @return string
     */
    public function getRubro()
    {
        return $this->rubro;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     *
     * @return RubroEntidad
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Get activo
     *
     * @return bool
     */
    public function getActivo()
    {
        return $this->activo;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->entidades = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add entidade
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\EntidadExterna $entidade
     *
     * @return RubroEntidad
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
}
