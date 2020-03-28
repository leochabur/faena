<?php

namespace GestionFaenaBundle\Entity\faena;

use Doctrine\ORM\Mapping as ORM;

/**
 * TransferirStock
 *
 * @ORM\Table(name="sp_st_trans_st")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\faena\TransferirStockRepository")
 */
class TransferirStock extends MovimientoStock
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
    private $movimientoDestino; //es el movimiento final de la transferencia (Es el proceso al cual se le envia la mercaderia, debe generar una EntradaStock)

    /**
     * @ORM\OneToOne(targetEntity="MovimientoStock", inversedBy="origen")
     * @ORM\JoinColumn(name="id_salida", referencedColumnName="id")
     */
    private $movimientoOrigen; //cual es el movimiento origen de la transferencia (Es el proceso del cual se retira la mercaderia, debe generar una SalidaStock)


    public function __toString()
    {
        return "Transferir";
    }

    public function getType()
    {
        return 5;
    }

    public function updateValues($promedio, $entityManager)
    {
     /*   $iterator = $this->getValores()->getIterator();
        $iterator->uasort(function ($first, $second) {
            return (int) $first->getAtributo()->getAtributo()->getPosition() > (int) $second->getAtributo()->getAtributo()->getPosition() ? 1 : -1;
        });
        foreach ($this->getValores() as $valor) {
            $valor->calcularValor($this);
        }*/
    }




    /**
     * Set procesoFnDayDestino
     *
     * @param \GestionFaenaBundle\Entity\faena\ProcesoFaenaDiaria $procesoFnDayDestino
     *
     * @return TransferirStock
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
     * @return TransferirStock
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
     * @return TransferirStock
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

    public static function getInstance()
    {
        return 5;
    }

    protected function updateVisible()
    {
        $this->setVisible(false);
    }
}
