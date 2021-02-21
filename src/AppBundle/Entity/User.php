<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="usuarios")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="GestionFaenaBundle\Entity\ProcesoFaena", inversedBy="usuarios")
     * @ORM\JoinTable(name="sp_proc_for_usr")
     */
    private $procesos;
    
    /**
     * @ORM\ManyToMany(targetEntity="Perfil")
     * @ORM\JoinTable(name="usuarios_perfiles"),
     *      joinColumns={@ORM\JoinColumn(name="id_usuario", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_perfil", referencedColumnName="id")}
     *      )
     */
    private $perfiles;


    public function __construct()
    {
        parent::__construct();
        $this->perfiles = new \Doctrine\Common\Collections\ArrayCollection();
    }


    public function isPerfilVenta()
    {
        foreach ($this->perfiles as $pf)
        {
            if ($pf->getIsVenta())
            {
                return true; 
            }
        }
        return false;
    }

    public function isPerfilFaena()
    {
        foreach ($this->perfiles as $pf)
        {
            if ($pf->getIsFaena())
            {
                return true; 
            }
        }
        return false;
    }

    /**
     * Add proceso
     *
     * @param \GestionFaenaBundle\Entity\ProcesoFaena $proceso
     *
     * @return User
     */
    public function addProceso(\GestionFaenaBundle\Entity\ProcesoFaena $proceso)
    {
        $this->procesos[] = $proceso;

        return $this;
    }

    /**
     * Remove proceso
     *
     * @param \GestionFaenaBundle\Entity\ProcesoFaena $proceso
     */
    public function removeProceso(\GestionFaenaBundle\Entity\ProcesoFaena $proceso)
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

    public function clearProcesos()
    {
        $this->procesos->clear();
    }

    /**
     * Add perfile
     *
     * @param \AppBundle\Entity\Perfil $perfile
     *
     * @return User
     */
    public function addPerfile(\AppBundle\Entity\Perfil $perfile)
    {
        $this->perfiles[] = $perfile;

        return $this;
    }

    /**
     * Remove perfile
     *
     * @param \AppBundle\Entity\Perfil $perfile
     */
    public function removePerfile(\AppBundle\Entity\Perfil $perfile)
    {
        $this->perfiles->removeElement($perfile);
    }

    /**
     * Get perfiles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPerfiles()
    {
        return $this->perfiles;
    }
}
