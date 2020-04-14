<?php

namespace GestionSigcerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DetalleSolicitud
 *
 * @ORM\Table(name="sig_det_sol")
 * @ORM\Entity(repositoryClass="GestionSigcerBundle\Repository\DetalleSolicitudRepository")
 */
class DetalleSolicitud
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
     * @var float
     *
     * @ORM\Column(name="pesoNeto", type="float")
     */
    private $pesoNeto;

    /**
     * @var float
     *
     * @ORM\Column(name="pesoBruto", type="float")
     */
    private $pesoBruto;

    /**
     * @var float
     *
     * @ORM\Column(name="cantidad", type="float")
     */
    private $cantidad;

    /**
     * @var string
     *
     * @ORM\Column(name="certificadoOrigen", type="string", length=255)
     */
    private $certificadoOrigen;

    /**
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\gestionBD\Articulo") 
    * @ORM\JoinColumn(name="id_art", referencedColumnName="id")
    */      
    private $articulo;

    /**
    * @ORM\ManyToOne(targetEntity="TropaSolicitud") 
    * @ORM\JoinColumn(name="id_tropa", referencedColumnName="id")
    */      
    private $tropa;

    /**
    * @ORM\ManyToOne(targetEntity="GestionSigcerBundle\Entity\opciones\Envase") 
    * @ORM\JoinColumn(name="id_env_primario", referencedColumnName="id")
    */      
    private $envasePrimario;

    /**
    * @ORM\ManyToOne(targetEntity="GestionSigcerBundle\Entity\opciones\Envase") 
    * @ORM\JoinColumn(name="id_env_secundario", referencedColumnName="id")
    */      
    private $envaseSecundario;

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
     * Set pesoNeto
     *
     * @param float $pesoNeto
     *
     * @return DetalleSolicitud
     */
    public function setPesoNeto($pesoNeto)
    {
        $this->pesoNeto = $pesoNeto;

        return $this;
    }

    /**
     * Get pesoNeto
     *
     * @return float
     */
    public function getPesoNeto()
    {
        return $this->pesoNeto;
    }

    /**
     * Set pesoBruto
     *
     * @param float $pesoBruto
     *
     * @return DetalleSolicitud
     */
    public function setPesoBruto($pesoBruto)
    {
        $this->pesoBruto = $pesoBruto;

        return $this;
    }

    /**
     * Get pesoBruto
     *
     * @return float
     */
    public function getPesoBruto()
    {
        return $this->pesoBruto;
    }

    /**
     * Set cantidad
     *
     * @param float $cantidad
     *
     * @return DetalleSolicitud
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return float
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set certificadoOrigen
     *
     * @param string $certificadoOrigen
     *
     * @return DetalleSolicitud
     */
    public function setCertificadoOrigen($certificadoOrigen)
    {
        $this->certificadoOrigen = $certificadoOrigen;

        return $this;
    }

    /**
     * Get certificadoOrigen
     *
     * @return string
     */
    public function getCertificadoOrigen()
    {
        return $this->certificadoOrigen;
    }
}

