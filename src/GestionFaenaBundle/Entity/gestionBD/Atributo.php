<?php

namespace GestionFaenaBundle\Entity\gestionBD;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Atributo
 *
 * @ORM\Table(name="sp_gst_atr_abs")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\gestionBD\AtributoRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="integer")
 * @ORM\DiscriminatorMap({1:"Atributo",2: "AtributoMedible", 3: "AtributoMedibleManual", 4: "AtributoMedibleAutomatico", 5: "AtributoInformable", 
                          6: "AtributoInformableExterno", 7: "AtributoInformableArbitrario"})
 */
abstract class Atributo
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
     * @ORM\Column(name="nombre", type="string", length=255)
     * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
     */
    private $nombre;

    /**
     * @var boolean
     *
     * @ORM\Column(name="activo", type="boolean", options={"default":true})
     */
    private $activo = true;

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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Atributo
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    public function __toString()
    {
        return $this->nombre;
    }

    public function getTipo()
    {

    }

    public function getCarga()
    {
        return 'Manual';
    }

    public function getUnidadMedida()
    {

    }

    public function getCalculo()
    {

    }

    public function getAjuste()
    {
        
    }

    public abstract function getEntityValorAtributo($atributo);///devuelve a que entidad debe instanciar para asignar el valor
    public abstract function getType(); //devuleve el codigo de cada clase

    public function getManual()
    {
        return false;
    }

    public function getFactoresCalculo()
    {
        return null;
    }

    public function getPosition()
    {
        return 0;
    }


    /**
     * Set activo
     *
     * @param boolean $activo
     *
     * @return Atributo
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Get activo
     *
     * @return boolean
     */
    public function getActivo()
    {
        return $this->activo;
    }

    abstract public function getClass();
}
