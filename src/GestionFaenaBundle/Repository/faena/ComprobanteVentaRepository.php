<?php

namespace GestionFaenaBundle\Repository\faena;

/**
 * ComprobanteVentaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ComprobanteVentaRepository extends \Doctrine\ORM\EntityRepository
{
	
	public function getComprobantesVenta(\DateTime $fecha) 
	{ 
			return $this->createQueryBuilder('c')
						->join('c.entidad', 'e')
						->where('c.fecha = :fecha')
						->andWhere('c.eliminado = :eliminado')
						->setParameter('fecha', $fecha)
						->setParameter('eliminado', false)
						->orderBy('e.valor')
						->getQuery()
						->getResult();
	} 

	public function getComprobantesVentaOfRubroEntidad(\DateTime $fecha, \GestionFaenaBundle\Entity\gestionBD\RubroEntidad $rubro) 
	{ 
			return $this->createQueryBuilder('c')
						->join('c.entidad', 'e')
						->join('e.rubro', 'r')
						->where('c.fecha = :fecha')
						->andWhere('c.eliminado = :eliminado')
						->andWhere('r = :rubro')
						->setParameter('fecha', $fecha)
						->setParameter('eliminado', false)
						->setParameter('rubro', $rubro)
						->getQuery()
						->getResult();
	} 

	public function getComprobantesVentaFinalizados(\DateTime $desde, \DateTime $hasta = null) 
	{ 
			$q = $this->createQueryBuilder('c');
			return  $q->join('c.entidad', 'e')
					  ->where($q->expr()->between('c.fecha',':desde',':hasta'))
					  ->andWhere('c.eliminado = :eliminado')
					  ->andWhere('c.finalizado = :finalizado')
					  ->setParameter('desde', $desde)
					  ->setParameter('hasta', ($hasta?$hasta:$desde))
					  ->setParameter('eliminado', false)
					  ->setParameter('finalizado', true)
					  ->orderBy('c.fecha')
					  ->addOrderBy('e.valor')
					  ->getQuery()
					  ->getResult();
	} 

	public function proximoComprobante(\GestionFaenaBundle\Entity\faena\ComprobanteVenta $comprobante) 
	{ 
			return $this->createQueryBuilder('c')
						->join('c.entidad', 'e')
						->where('c.fecha = :fecha')
						->andWhere('c.eliminado = :eliminado')
						->andWhere('c.finalizado = :finalizado')
						->andWhere('e.valor >= :valor')
						->andWhere('c <> :comprobante')
						->setParameter('fecha', $comprobante->getFecha())
						->setParameter('eliminado', false)
						->setParameter('finalizado', true)
						->setParameter('valor', $comprobante->getEntidad()->getValor())
						->setParameter('comprobante', $comprobante)
						->orderBy('c.fecha')
						->addOrderBy('e.valor', 'ASC')
						->setMaxResults(1)
						->getQuery()
						->getOneOrNullResult();
	} 

	public function anteriorComprobante(\GestionFaenaBundle\Entity\faena\ComprobanteVenta $comprobante) 
	{ 
			return $this->createQueryBuilder('c')
						->join('c.entidad', 'e')
						->where('c.fecha = :fecha')
						->andWhere('c.eliminado = :eliminado')
						->andWhere('c.finalizado = :finalizado')
						->andWhere('e.valor <= :valor')
						->andWhere('c <> :comprobante')
						->setParameter('fecha', $comprobante->getFecha())
						->setParameter('eliminado', false)
						->setParameter('finalizado', true)
						->setParameter('valor', $comprobante->getEntidad()->getValor())
						->setParameter('comprobante', $comprobante)
						->orderBy('c.fecha')
						->addOrderBy('e.valor', 'ASC')
						->setMaxResults(1)
						->getQuery()
						->getOneOrNullResult();
	} 

	public function getComprobanteConEntidadYFecha(\DateTime $fecha, $entidad) 
	{ 
			return $this->createQueryBuilder('c')
						->where('c.fecha = :fecha')
						->andWhere('c.eliminado = :eliminado')
						->andWhere('c.entidad = :entidad')
						->setParameter('fecha', $fecha)
						->setParameter('eliminado', false)
						->setParameter('entidad', $entidad)
						->getQuery()
						->getOneOrNullResult();
	} 

	public function getUltimosComprobantesVenta() 
	{ 
			return $this->createQueryBuilder('c')
						->where('c.finalizado = :finalizado')
						->andWhere('c.eliminado = :eliminado')
						->andWhere('c.confirmado = :confirmado')
						->setParameter('finalizado', true)
						->setParameter('eliminado', false)
						->setParameter('confirmado', false)
						->getQuery()
						->getResult();
	} 
}
