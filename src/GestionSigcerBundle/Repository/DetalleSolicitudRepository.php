<?php

namespace GestionSigcerBundle\Repository;

/**
 * DetalleSolicitudRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DetalleSolicitudRepository extends \Doctrine\ORM\EntityRepository
{

	public function getDetalleConTropa(\GestionSigcerBundle\Entity\TropaSolicitud $tropa) 
	{ 
	    return $this->createQueryBuilder('d')
	    			->where('d.tropa= :tropa')
	    			->setParameter('tropa', $tropa)
			        ->getQuery()
			        ->getOneOrNullResult(); 
	} 
}
