<?php

namespace GestionSigcerBundle\Entity\opciones;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Producto
 *
 * @ORM\Table(name="sig_prdo_det")
 * @ORM\Entity(repositoryClass="GestionSigcerBundle\Repository\opciones\ProductoRepository")
 */
class Producto
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
     * @var string
     *
     * @ORM\Column(name="codigoCapa", type="string", length=255)
    * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
     */
    private $codigoCapa;

    /**
     * @var string
     *
     * @ORM\Column(name="marca", type="string", length=255)
    * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
     */
    private $marca;

    /**
    * @ORM\ManyToOne(targetEntity="Envase") 
    * @ORM\JoinColumn(name="id_envase_primario", referencedColumnName="id")
    * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
    */      
    private $envasePrimario;

    /**
    * @ORM\ManyToOne(targetEntity="Envase") 
    * @ORM\JoinColumn(name="id_envase_secundario", referencedColumnName="id")
    * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
    */      
    private $envaseSecundario;

    /**
    * @ORM\Column(name="ajusteBruto", type="float")
    * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
     */
    private $ajusteBruto;

    /**
    * @ORM\Column(name="ajusteNeto", type="float")
    * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
     */
    private $ajusteNeto;

    /**
    * @ORM\Column(name="ajusteCantidad", type="float")
    * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
     */
    private $ajusteCantidad;
    
    /**
     * @ORM\Column(name="destino", type="string", length=2, options={"default"="MI"})
     */
    private $destino; //MI : Mercado Interno  -   EX : Exportacion

    public function __toString()
    {
        return strtoupper($this->nombre." - ".$this->codigoCapa);
    }

    public function getDestinoTxt()
    {
        return ($this->destino=='MI'?'Mercado Interno':'Exportacion');
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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Producto
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

    /**
     * Set codigoCapa
     *
     * @param string $codigoCapa
     *
     * @return Producto
     */
    public function setCodigoCapa($codigoCapa)
    {
        $this->codigoCapa = $codigoCapa;

        return $this;
    }

    /**
     * Get codigoCapa
     *
     * @return string
     */
    public function getCodigoCapa()
    {
        return $this->codigoCapa;
    }

    /**
     * Set marca
     *
     * @param string $marca
     *
     * @return Producto
     */
    public function setMarca($marca)
    {
        $this->marca = $marca;

        return $this;
    }

    /**
     * Get marca
     *
     * @return string
     */
    public function getMarca()
    {
        return $this->marca;
    }

    /**
     * Set envasePrimario
     *
     * @param \GestionSigcerBundle\Entity\opciones\Envase $envasePrimario
     *
     * @return Producto
     */
    public function setEnvasePrimario(\GestionSigcerBundle\Entity\opciones\Envase $envasePrimario = null)
    {
        $this->envasePrimario = $envasePrimario;

        return $this;
    }

    /**
     * Get envasePrimario
     *
     * @return \GestionSigcerBundle\Entity\opciones\Envase
     */
    public function getEnvasePrimario()
    {
        return $this->envasePrimario;
    }

    /**
     * Set envaseSecundario
     *
     * @param \GestionSigcerBundle\Entity\opciones\Envase $envaseSecundario
     *
     * @return Producto
     */
    public function setEnvaseSecundario(\GestionSigcerBundle\Entity\opciones\Envase $envaseSecundario = null)
    {
        $this->envaseSecundario = $envaseSecundario;

        return $this;
    }

    /**
     * Get envaseSecundario
     *
     * @return \GestionSigcerBundle\Entity\opciones\Envase
     */
    public function getEnvaseSecundario()
    {
        return $this->envaseSecundario;
    }

    /**
     * Set ajusteBruto
     *
     * @param float $ajusteBruto
     *
     * @return Producto
     */
    public function setAjusteBruto($ajusteBruto)
    {
        $this->ajusteBruto = $ajusteBruto;

        return $this;
    }

    /**
     * Get ajusteBruto
     *
     * @return float
     */
    public function getAjusteBruto()
    {
        return $this->ajusteBruto;
    }

    /**
     * Set ajusteNeto
     *
     * @param float $ajusteNeto
     *
     * @return Producto
     */
    public function setAjusteNeto($ajusteNeto)
    {
        $this->ajusteNeto = $ajusteNeto;

        return $this;
    }

    /**
     * Get ajusteNeto
     *
     * @return float
     */
    public function getAjusteNeto()
    {
        return $this->ajusteNeto;
    }

    /**
     * Set ajusteCantidad
     *
     * @param float $ajusteCantidad
     *
     * @return Producto
     */
    public function setAjusteCantidad($ajusteCantidad)
    {
        $this->ajusteCantidad = $ajusteCantidad;

        return $this;
    }

    /**
     * Get ajusteCantidad
     *
     * @return float
     */
    public function getAjusteCantidad()
    {
        return $this->ajusteCantidad;
    }

    /**
     * Set destino
     *
     * @param string $destino
     *
     * @return Producto
     */
    public function setDestino($destino)
    {
        $this->destino = $destino;

        return $this;
    }

    /**
     * Get destino
     *
     * @return string
     */
    public function getDestino()
    {
        return $this->destino;
    }
}
