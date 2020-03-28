<?php

namespace GestionFaenaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * FaenaDiaria
 *
 * @ORM\Table(name="sp_fan_day")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\FaenaDiariaRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity("fechaFaena", message="Faena existente para la fecha seleccionada")
 */
class FaenaDiaria
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
     * @ORM\Column(name="fechaFaena", type="date")
     */
    private $fechaFaena;

    /**
     * @var bool
     *
     * @ORM\Column(name="isActive", type="boolean")
     */
    private $isActive;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaAlta", type="datetime")
     */
    private $fechaAlta;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaCierre", type="datetime", nullable=true)
     */
    private $fechaCierre;

    /**
     * @var bool
     *
     * @ORM\Column(name="finalizada", type="boolean")
     */
    private $finalizada;

    /**
     * @ORM\ManyToMany(targetEntity="GestionFaenaBundle\Entity\faena\ProcesoFaenaDiaria", cascade={"persist"})
     * @ORM\JoinTable(name="sp_proc_for_fan_day",
     *      joinColumns={@ORM\JoinColumn(name="id_fan_day", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_proc_fan_day", referencedColumnName="id")}
     *      )
     */
    private $procesos;

    /**
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User") 
    * @ORM\JoinColumn(name="id_user_open", referencedColumnName="id")
    */      
    private $userCreate;

    /**
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User") 
    * @ORM\JoinColumn(name="id_user_close", referencedColumnName="id", nullable=true)
    */      
    private $userClose;


    /*Dado un ProcesoFaena devuelve el correspondiente ProcesoFaenaDiaria de la faena
    public function getProcesoDiario(\GestionFaenaBundle\Entity\ProcesoFaena $proceso)
    {
        foreach ($this->procesos as $proc) 
        {
            if ($proc->getProcesoFaena() == $proceso)
                return $proc;
        }
        return null;
    }*/

    public function getProceso($id)
    {
        foreach ($this->procesos as $proc) {
            if ( $proc->getProcesoFaena()->getId() == $id)
                return $proc;
        }
        return null;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->fechaAlta = new \DateTime();
        $this->isActive = true;
        $this->finalizada = false;
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
     * Set fechaFaena
     *
     * @param \DateTime $fechaFaena
     *
     * @return FaenaDiaria
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
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return FaenaDiaria
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set fechaAlta
     *
     * @param \DateTime $fechaAlta
     *
     * @return FaenaDiaria
     */
    public function setFechaAlta($fechaAlta)
    {
        $this->fechaAlta = $fechaAlta;

        return $this;
    }

    /**
     * Get fechaAlta
     *
     * @return \DateTime
     */
    public function getFechaAlta()
    {
        return $this->fechaAlta;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->procesos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add proceso
     *
     * @param \GestionFaenaBundle\Entity\faena\ProcesoFaenaDiaria $proceso
     *
     * @return FaenaDiaria
     */
    public function addProceso(\GestionFaenaBundle\Entity\faena\ProcesoFaenaDiaria $proceso)
    {
        $this->procesos[] = $proceso;

        return $this;
    }

    /**
     * Remove proceso
     *
     * @param \GestionFaenaBundle\Entity\faena\ProcesoFaenaDiaria $proceso
     */
    public function removeProceso(\GestionFaenaBundle\Entity\faena\ProcesoFaenaDiaria $proceso)
    {
        $this->procesos->removeElement($proceso);
    }

    /**
     * Get procesos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProcesos()
    {
        return $this->procesos;
    }

    public function __toString()
    {
        return $this->fechaFaena->format('d/m/Y');
    }

    /**
     * Set finalizada
     *
     * @param boolean $finalizada
     *
     * @return FaenaDiaria
     */
    public function setFinalizada($finalizada)
    {
        $this->finalizada = $finalizada;

        return $this;
    }

    /**
     * Get finalizada
     *
     * @return boolean
     */
    public function getFinalizada()
    {
        return $this->finalizada;
    }

    /**
     * Set userCreate
     *
     * @param \AppBundle\Entity\User $userCreate
     *
     * @return FaenaDiaria
     */
    public function setUserCreate(\AppBundle\Entity\User $userCreate = null)
    {
        $this->userCreate = $userCreate;

        return $this;
    }

    /**
     * Get userCreate
     *
     * @return \AppBundle\Entity\User
     */
    public function getUserCreate()
    {
        return $this->userCreate;
    }

    public function getState()
    {
        return $this->finalizada?'Finalizada':'Pendiente';
    }

    /**
     * Set fechaCierre
     *
     * @param \DateTime $fechaCierre
     *
     * @return FaenaDiaria
     */
    public function setFechaCierre($fechaCierre)
    {
        $this->fechaCierre = $fechaCierre;

        return $this;
    }

    /**
     * Get fechaCierre
     *
     * @return \DateTime
     */
    public function getFechaCierre()
    {
        return $this->fechaCierre;
    }

    /**
     * Set userClose
     *
     * @param \AppBundle\Entity\User $userClose
     *
     * @return FaenaDiaria
     */
    public function setUserClose(\AppBundle\Entity\User $userClose = null)
    {
        $this->userClose = $userClose;

        return $this;
    }

    /**
     * Get userClose
     *
     * @return \AppBundle\Entity\User
     */
    public function getUserClose()
    {
        return $this->userClose;
    }

    public function faenaSinMovimientos() ///determina si la faena tiene movimientos para agluno de sus proceso, de tenerlos no se puede elimimnar
    {
        foreach ($this->procesos as $proc) {
            if (!$proc->getMovimientos()->isEmpty())
                return false;
        }
        return true;
    }
}
