<?php
namespace GestionFaenaBundle\Event;

use Symfony\Component\EventDispatcher\Event;

use GestionFaenaBundle\Entity\faena\MovimientoStock;

class MovimientoEvent extends Event
{
    const NAME = 'movimiento.generar';

    protected $movimiento, $em;

    public function __construct(MovimientoStock $movimiento, $em)
    {
        $this->movimiento = $movimiento;
        $this->em = $em;
    }

    public function getMovimiento()
    {
        return $this->movimiento;
    }
    
    public function getManager()
    {
        return $this->em;
    }
}