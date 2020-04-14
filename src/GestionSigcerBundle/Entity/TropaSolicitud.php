<?php

namespace GestionSigcerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * TropaSolicitud
 *
 * @ORM\Table(name="sig_trp_sol")
 * @ORM\Entity(repositoryClass="GestionSigcerBundle\Repository\TropaSolicitudRepository")
 */
class TropaSolicitud
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
     * @var \DateTime
     *
     * @ORM\Column(name="fechaCongelado", type="datetime")
     * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
     */
    private $fechaCongelado;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaElaboracion", type="datetime")
     * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
     */
    private $fechaElaboracion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaFaena", type="datetime")
     * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
     */
    private $fechaFaena;

    /**
     * @var string
     *
     * @ORM\Column(name="fechaVto", type="datetime", length=255)
     * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
     */
    private $fechaVto;

    /**
     * @var string
     *
     * @ORM\Column(name="numeroTropa", type="string", length=255)
     * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
     */
    private $numeroTropa;

    /**
     * @var string
     *
     * @ORM\Column(name="lote", type="string", length=255)
     * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
     */
    private $lote;

    /**
    * @ORM\ManyToOne(targetEntity="GrupoSolicitud", inversedBy="tropas") 
    * @ORM\JoinColumn(name="id_gpo_solic", referencedColumnName="id")
    */      
    private $grupoSolicitud;

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
     * Set fechaCongelado
     *
     * @param \DateTime $fechaCongelado
     *
     * @return TropaSolicitud
     */
    public function setFechaCongelado($fechaCongelado)
    {
        $this->fechaCongelado = $fechaCongelado;

        return $this;
    }

    /**
     * Get fechaCongelado
     *
     * @return \DateTime
     */
    public function getFechaCongelado()
    {
        return $this->fechaCongelado;
    }

    /**
     * Set fechaElaboracion
     *
     * @param \DateTime $fechaElaboracion
     *
     * @return TropaSolicitud
     */
    public function setFechaElaboracion($fechaElaboracion)
    {
        $this->fechaElaboracion = $fechaElaboracion;

        return $this;
    }

    /**
     * Get fechaElaboracion
     *
     * @return \DateTime
     */
    public function getFechaElaboracion()
    {
        return $this->fechaElaboracion;
    }

    /**
     * Set fechaFaena
     *
     * @param \DateTime $fechaFaena
     *
     * @return TropaSolicitud
     */
    public function setFechaFaena($fechaFaena)
    {
        $this->fechaFaena = $fechaFaena;

        return $this;
    }

    /**
     * Get fechaFaena
     *
     * @return \DateTime
     */
    public function getFechaFaena()
    {
        return $this->fechaFaena;
    }

    /**
     * Set fechaVto
     *
     * @param string $fechaVto
     *
     * @return TropaSolicitud
     */
    public function setFechaVto($fechaVto)
    {
        $this->fechaVto = $fechaVto;

        return $this;
    }

    /**
     * Get fechaVto
     *
     * @return string
     */
    public function getFechaVto()
    {
        return $this->fechaVto;
    }

    /**
     * Set numeroTropa
     *
     * @param string $numeroTropa
     *
     * @return TropaSolicitud
     */
    public function setNumeroTropa($numeroTropa)
    {
        $this->numeroTropa = $numeroTropa;

        return $this;
    }

    /**
     * Get numeroTropa
     *
     * @return string
     */
    public function getNumeroTropa()
    {
        return $this->numeroTropa;
    }

    /**
     * Set lote
     *
     * @param string $lote
     *
     * @return TropaSolicitud
     */
    public function setLote($lote)
    {
        $this->lote = $lote;

        return $this;
    }

    /**
     * Get lote
     *
     * @return string
     */
    public function getLote()
    {
        return $this->lote;
    }

    /**
     * Set grupoSolicitud
     *
     * @param \GestionSigcerBundle\Entity\GrupoSolicitud $grupoSolicitud
     *
     * @return TropaSolicitud
     */
    public function setGrupoSolicitud(\GestionSigcerBundle\Entity\GrupoSolicitud $grupoSolicitud = null)
    {
        $this->grupoSolicitud = $grupoSolicitud;

        return $this;
    }

    /**
     * Get grupoSolicitud
     *
     * @return \GestionSigcerBundle\Entity\GrupoSolicitud
     */
    public function getGrupoSolicitud()
    {
        return $this->grupoSolicitud;
    }
}
