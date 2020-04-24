<?php

namespace GestionSigcerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * GrupoSolicitud
 *
 * @ORM\Table(name="sig_grpo_solic")
 * @ORM\Entity(repositoryClass="GestionSigcerBundle\Repository\GrupoSolicitudRepository")
 */
class GrupoSolicitud
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
     * @ORM\Column(name="version", type="string", length=255)
     */
    private $version;

    /**
     * @var string
     *
     * @ORM\Column(name="codigoEstablecimiento", type="string", length=255)
     */
    private $codigoEstablecimiento;

    /**
     * @var string
     *
     * @ORM\Column(name="roleEstablecimiento", type="string", length=255)
     */
    private $roleEstablecimiento;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
     */
    private $fecha;

    /**
    * @ORM\ManyToOne(targetEntity="GestionSigcerBundle\Entity\opciones\PaisDestino") 
    * @ORM\JoinColumn(name="id_pais_ddestino", referencedColumnName="id")
     * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
    */      
    private $paisDestino;

    /**
     * @ORM\OneToMany(targetEntity="TropaSolicitud", mappedBy="grupoSolicitud")
     */
    private $tropas;

    /**
     * @ORM\OneToMany(targetEntity="Solicitud", mappedBy="grupo")
     */
    private $solicitudes;

    /**
     * @ORM\Column(name="inicioPrecinto", type="integer")
     * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
     */
    private $inicioPrecinto;

    public function getTropa()
    {
        foreach ($this->tropas as $t) {
            return $t;
        }
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

    public function __toString()
    {
        return $this->fecha->format('d/m/Y');
    }

    /**
     * Set version
     *
     * @param string $version
     *
     * @return GrupoSolicitud
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set codigoEstablecimiento
     *
     * @param string $codigoEstablecimiento
     *
     * @return GrupoSolicitud
     */
    public function setCodigoEstablecimiento($codigoEstablecimiento)
    {
        $this->codigoEstablecimiento = $codigoEstablecimiento;

        return $this;
    }

    /**
     * Get codigoEstablecimiento
     *
     * @return string
     */
    public function getCodigoEstablecimiento()
    {
        return $this->codigoEstablecimiento;
    }

    /**
     * Set roleEstablecimiento
     *
     * @param string $roleEstablecimiento
     *
     * @return GrupoSolicitud
     */
    public function setRoleEstablecimiento($roleEstablecimiento)
    {
        $this->roleEstablecimiento = $roleEstablecimiento;

        return $this;
    }

    /**
     * Get roleEstablecimiento
     *
     * @return string
     */
    public function getRoleEstablecimiento()
    {
        return $this->roleEstablecimiento;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return GrupoSolicitud
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set paisDestino
     *
     * @param \GestionSigcerBundle\Entity\opciones\PaisDestino $paisDestino
     *
     * @return GrupoSolicitud
     */
    public function setPaisDestino(\GestionSigcerBundle\Entity\opciones\PaisDestino $paisDestino = null)
    {
        $this->paisDestino = $paisDestino;

        return $this;
    }

    /**
     * Get paisDestino
     *
     * @return \GestionSigcerBundle\Entity\opciones\PaisDestino
     */
    public function getPaisDestino()
    {
        return $this->paisDestino;
    }

    /**
     * Add tropa
     *
     * @param \GestionSigcerBundle\Entity\TropaSolicitud $tropa
     *
     * @return GrupoSolicitud
     */
    public function addTropa(\GestionSigcerBundle\Entity\TropaSolicitud $tropa)
    {
        $this->tropas[] = $tropa;

        return $this;
    }

    /**
     * Remove tropa
     *
     * @param \GestionSigcerBundle\Entity\TropaSolicitud $tropa
     */
    public function removeTropa(\GestionSigcerBundle\Entity\TropaSolicitud $tropa)
    {
        $this->tropas->removeElement($tropa);
    }

    /**
     * Get tropas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTropas()
    {
        return $this->tropas;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tropas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->solicitudes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add solicitude
     *
     * @param \GestionSigcerBundle\Entity\Solicitud $solicitude
     *
     * @return GrupoSolicitud
     */
    public function addSolicitude(\GestionSigcerBundle\Entity\Solicitud $solicitude)
    {
        $this->solicitudes[] = $solicitude;

        return $this;
    }

    /**
     * Remove solicitude
     *
     * @param \GestionSigcerBundle\Entity\Solicitud $solicitude
     */
    public function removeSolicitude(\GestionSigcerBundle\Entity\Solicitud $solicitude)
    {
        $this->solicitudes->removeElement($solicitude);
    }

    /**
     * Get solicitudes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSolicitudes()
    {
        return $this->solicitudes;
    }

    /**
     * Set inicioPrecinto
     *
     * @param integer $inicioPrecinto
     *
     * @return GrupoSolicitud
     */
    public function setInicioPrecinto($inicioPrecinto)
    {
        $this->inicioPrecinto = $inicioPrecinto;

        return $this;
    }

    /**
     * Get inicioPrecinto
     *
     * @return integer
     */
    public function getInicioPrecinto()
    {
        return $this->inicioPrecinto;
    }
}
