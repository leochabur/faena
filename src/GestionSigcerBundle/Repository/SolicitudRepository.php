<?php

namespace GestionSigcerBundle\Repository;

/**
 * SolicitudRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SolicitudRepository extends \Doctrine\ORM\EntityRepository
{
	public function findSolicitudes(\GestionSigcerBundle\Entity\GrupoSolicitud $grupo,
									\GestionSigcerBundle\Entity\opciones\Zona $zona) 
	{ 
	    return $this->createQueryBuilder('s')
	    			->where('s.zona = :zona AND s.grupo = :grupo')
	    			->setParameter('zona', $zona)
	    			->setParameter('grupo', $grupo)
			        ->addOrderBy('s.zona')
			        ->getQuery()
			        ->getResult(); 
	} 
}
