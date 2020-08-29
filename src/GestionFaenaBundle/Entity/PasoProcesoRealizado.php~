<?php

namespace GestionFaenaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PasoProcesoRealizado
 *
 * @ORM\Table(name="sp_gst_pso_proc_realice")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\PasoProcesoRealizadoRepository")
 */
class PasoProcesoRealizado
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
     * @ORM\Column(name="fechaAccion", type="datetime")
     */
    private $fechaAccion;

    /**
     * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\faena\ProcesoFaenaDiaria")
     * @ORM\JoinColumn(name="id_proc_fan_day", referencedColumnName="id")
    */    
    private $procesoFaenaDiaria;

    /**
     * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\FaenaDiaria")
     * @ORM\JoinColumn(name="id_fan_day", referencedColumnName="id")
    */    
    private $faenaDiaria;

    /**
     * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\PasoProceso")
     * @ORM\JoinColumn(name="id_paso_proc", referencedColumnName="id")
    */    
    private $paso;

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
     * Set fechaAccion
     *
     * @param \DateTime $fechaAccion
     *
     * @return PasoProcesoRealizado
     */
    public function setFechaAccion($fechaAccion)
    {
        $this->fechaAccion = $fechaAccion;

        return $this;
    }

    /**
     * Get fechaAccion
     *
     * @return \DateTime
     */
    public function getFechaAccion()
    {
        return $this->fechaAccion;
    }

    /**
     * Set procesoFaenaDiaria
     *
     * @param \GestionFaenaBundle\Entity\faena\ProcesoFaenaDiaria $procesoFaenaDiaria
     *
     * @return PasoProcesoRealizado
     */
    public function setProcesoFaenaDiaria(\GestionFaenaBundle\Entity\faena\ProcesoFaenaDiaria $procesoFaenaDiaria = null)
    {
        $this->procesoFaenaDiaria = $procesoFaenaDiaria;

        return $this;
    }

    /**
     * Get procesoFaenaDiaria
     *
     * @return \GestionFaenaBundle\Entity\faena\ProcesoFaenaDiaria
     */
    public function getProcesoFaenaDiaria()
    {
        return $this->procesoFaenaDiaria;
    }

    /**
     * Set faenaDiaria
     *
     * @param \GestionFaenaBundle\Entity\FaenaDiaria $faenaDiaria
     *
     * @return PasoProcesoRealizado
     */
    public function setFaenaDiaria(\GestionFaenaBundle\Entity\FaenaDiaria $faenaDiaria = null)
    {
        $this->faenaDiaria = $faenaDiaria;

        return $this;
    }

    /**
     * Get faenaDiaria
     *
     * @return \GestionFaenaBundle\Entity\FaenaDiaria
     */
    public function getFaenaDiaria()
    {
        return $this->faenaDiaria;
    }

    /**
     * Set paso
     *
     * @param \GestionFaenaBundle\Entity\PasoProceso $paso
     *
     * @return PasoProcesoRealizado
     */
    public function setPaso(\GestionFaenaBundle\Entity\PasoProceso $paso = null)
    {
        $this->paso = $paso;

        return $this;
    }

    /**
     * Get paso
     *
     * @return \GestionFaenaBundle\Entity\PasoProceso
     */
    public function getPaso()
    {
        return $this->paso;
    }
}
