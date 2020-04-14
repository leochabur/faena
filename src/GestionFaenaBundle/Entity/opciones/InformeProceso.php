<?php

namespace GestionFaenaBundle\Entity\opciones;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * InformeProceso
 *
 * @ORM\Table(name="opciones_informe_proceso")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\opciones\InformeProcesoRepository")
 */
class InformeProceso
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
     * @ORM\ManyToMany(targetEntity="GestionFaenaBundle\Entity\faena\ConceptoMovimiento")
     * @ORM\JoinTable(name="sp_proc_con_mov_por_proceso",
     *      joinColumns={@ORM\JoinColumn(name="id_inf_proc", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_con_mov", referencedColumnName="id", unique=true)}
     *      )
    /**
     * @Assert\Count(
     *      min = 1,
     *      minMessage = "Debe, al menos, seleccionar un concepto"
     * )
     */
    private $conceptos;

    /**
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\ProcesoFaena") 
    * @ORM\JoinColumn(name="id_proc_fan", referencedColumnName="id")
    * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
    */      
    private $proceso;

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
     * Constructor
     */
    public function __construct()
    {
        $this->conceptos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add concepto
     *
     * @param \GestionFaenaBundle\Entity\faena\ConceptoMovimiento $concepto
     *
     * @return InformeProceso
     */
    public function addConcepto(\GestionFaenaBundle\Entity\faena\ConceptoMovimiento $concepto)
    {
        $this->conceptos[] = $concepto;

        return $this;
    }

    /**
     * Remove concepto
     *
     * @param \GestionFaenaBundle\Entity\faena\ConceptoMovimiento $concepto
     */
    public function removeConcepto(\GestionFaenaBundle\Entity\faena\ConceptoMovimiento $concepto)
    {
        $this->conceptos->removeElement($concepto);
    }

    /**
     * Get conceptos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getConceptos()
    {
        return $this->conceptos;
    }

    /**
     * Set proceso
     *
     * @param \GestionFaenaBundle\Entity\ProcesoFaena $proceso
     *
     * @return InformeProceso
     */
    public function setProceso(\GestionFaenaBundle\Entity\ProcesoFaena $proceso = null)
    {
        $this->proceso = $proceso;

        return $this;
    }

    /**
     * Get proceso
     *
     * @return \GestionFaenaBundle\Entity\ProcesoFaena
     */
    public function getProceso()
    {
        return $this->proceso;
    }
}
