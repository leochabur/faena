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
    

    public function __construct()
    {
        parent::__construct();
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
}
