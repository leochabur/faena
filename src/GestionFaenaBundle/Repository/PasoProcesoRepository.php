<?php

namespace GestionFaenaBundle\Repository;

/**
 * PasoProcesoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PasoProcesoRepository extends \Doctrine\ORM\EntityRepository
{

	public function findPasoProceso(\GestionFaenaBundle\Entity\ProcesoFaena $proceso,
									\GestionFaenaBundle\Entity\GrupoMovimientosAutomatico $grupo) 
	{ 
	    return $this->createQueryBuilder('g')
			        ->where('g.procesoFaena = :proceso')
			        ->andWhere('g.grupoMovimiento = :grupo')
			        ->setParameter('proceso', $proceso)
			        ->setParameter('grupo', $grupo)
			        ->getQuery()
			        ->getOneOrNullResult(); 
	} 

	public function findPasoProcesoArtAtrConc(\GestionFaenaBundle\Entity\ProcesoFaena $proceso,
											  \GestionFaenaBundle\Entity\gestionBD\ArticuloAtributoConcepto $articulo) 
	{ 
	    return $this->createQueryBuilder('g')
			        ->where('g.procesoFaena = :proceso')
			        ->andWhere('g.articuloAtributoConcepto = :articulo')
			        ->setParameter('proceso', $proceso)
			        ->setParameter('articulo', $articulo)
			        ->getQuery()
			        ->getOneOrNullResult(); 
	} 
}
