<?php

namespace GestionSigcerBundle\Entity\opciones;

use Doctrine\ORM\Mapping as ORM;

/**
 * Region
 *
 * @ORM\Table(name="sig_reg_rpto")
 * @ORM\Entity(repositoryClass="GestionSigcerBundle\Repository\opciones\RegionRepository")
 */
class Region
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
     * @ORM\Column(name="region", type="string", length=255)
     */
    private $region;

    /**
     * @ORM\OneToMany(targetEntity="Zona", mappedBy="region")
     */
    private $zonas;

    public function __toString()
    {
        return $this->region;
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
     * Set region
     *
     * @param string $region
     *
     * @return Region
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->zonas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add zona
     *
     * @param \GestionSigcerBundle\Entity\opciones\Zona $zona
     *
     * @return Region
     */
    public function addZona(\GestionSigcerBundle\Entity\opciones\Zona $zona)
    {
        $this->zonas[] = $zona;

        return $this;
    }

    /**
     * Remove zona
     *
     * @param \GestionSigcerBundle\Entity\opciones\Zona $zona
     */
    public function removeZona(\GestionSigcerBundle\Entity\opciones\Zona $zona)
    {
        $this->zonas->removeElement($zona);
    }

    /**
     * Get zonas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getZonas()
    {
        return $this->zonas;
    }
}
