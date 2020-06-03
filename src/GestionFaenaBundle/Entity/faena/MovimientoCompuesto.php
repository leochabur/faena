<?php

namespace GestionFaenaBundle\Entity\faena;

use Doctrine\ORM\Mapping as ORM;

/**
 * MovimientoCompuesto
 *
 * @ORM\Table(name="sp_st_mov_comp")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\faena\MovimientoCompuestoRepository")
 */
abstract class MovimientoCompuesto extends MovimientoStock
{
    /**
    * @ORM\ManyToOne(targetEntity="ProcesoFaenaDiaria") 
    * @ORM\JoinColumn(name="id_proc_fan_day_dest", referencedColumnName="id")
    */      
    private $procesoFnDayDestino;

    /**
     * @ORM\OneToOne(targetEntity="MovimientoStock", inversedBy="destino")
     * @ORM\JoinColumn(name="id_entrada", referencedColumnName="id")
     */
    private $movimientoDestino; //es el movimiento final de la transferencia/transformacion (Es el proceso al cual se le envia la mercaderia, debe generar una EntradaStock)

    /**
     * @ORM\OneToOne(targetEntity="MovimientoStock", inversedBy="origen")
     * @ORM\JoinColumn(name="id_salida", referencedColumnName="id")
     */
    private $movimientoOrigen; //cual es el movimiento origen de la transferencia/transformacion (Es el proceso del cual se retira la mercaderia, debe generar una SalidaStock)


    /**
     * Set procesoFnDayDestino
     *
     * @param \GestionFaenaBundle\Entity\faena\ProcesoFaenaDiaria $procesoFnDayDestino
     *
     * @return MovimientoCompuesto
     */
    public function setProcesoFnDayDestino(\GestionFaenaBundle\Entity\faena\ProcesoFaenaDiaria $procesoFnDayDestino = null)
    {
        $this->procesoFnDayDestino = $procesoFnDayDestino;

        return $this;
    }

    /**
     * Get procesoFnDayDestino
     *
     * @return \GestionFaenaBundle\Entity\faena\ProcesoFaenaDiaria
     */
    public function getProcesoFnDayDestino()
    {
        return $this->procesoFnDayDestino;
    }

    /**
     * Set movimientoDestino
     *
     * @param \GestionFaenaBundle\Entity\faena\MovimientoStock $movimientoDestino
     *
     * @return MovimientoCompuesto
     */
    public function setMovimientoDestino(\GestionFaenaBundle\Entity\faena\MovimientoStock $movimientoDestino = null)
    {
        $this->movimientoDestino = $movimientoDestino;

        return $this;
    }

    /**
     * Get movimientoDestino
     *
     * @return \GestionFaenaBundle\Entity\faena\MovimientoStock
     */
    public function getMovimientoDestino()
    {
        return $this->movimientoDestino;
    }

    /**
     * Set movimientoOrigen
     *
     * @param \GestionFaenaBundle\Entity\faena\MovimientoStock $movimientoOrigen
     *
     * @return MovimientoCompuesto
     */
    public function setMovimientoOrigen(\GestionFaenaBundle\Entity\faena\MovimientoStock $movimientoOrigen = null)
    {
        $this->movimientoOrigen = $movimientoOrigen;

        return $this;
    }

    /**
     * Get movimientoOrigen
     *
     * @return \GestionFaenaBundle\Entity\faena\MovimientoStock
     */
    public function getMovimientoOrigen()
    {
        return $this->movimientoOrigen;
    }
}
