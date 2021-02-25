<?php

namespace GestionFaenaBundle\Repository\faena;

/**
 * ItemCargaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ItemCargaRepository extends \Doctrine\ORM\EntityRepository
{

	public function itemsAImprimir(\GestionFaenaBundle\Entity\faena\ComprobanteVenta $comprobante, $oficial) 
	{ 
	    $q = $this->createQueryBuilder('it')
	    		   ->join('it.articulo', 'art')
	    		   ->join('art.categoria', 'cat')
	    		   ->join('it.tipoVenta', 'tpo')
	    		   ->where('it.comprobante = :comprobante');
	    if ($oficial)
	    {
	    	$q->andWhere('tpo.oficial = :oficial')
	    	  ->setParameter('oficial', $oficial);
	    }
	    return $q->setParameter('comprobante', $comprobante)
		          ->addOrderBy('cat.grupo')
		          ->addOrderBy('art.codigoInterno')
		          ->getQuery()
		          ->getResult(); 
	} 

	public function itemsOrdenCargaAImprimir(\GestionFaenaBundle\Entity\faena\OrdenCarga $orden) 
	{ 
	    $q = $this->createQueryBuilder('it')
	    		   ->join('it.articulo', 'art')
	    		   ->join('art.categoria', 'cat')
	    		   ->join('it.comprobante', 'comp')
	    		   ->where('comp.ordenCarga = :orden')
	    		   ->andWhere('comp.eliminado = :eliminado')
				   ->setParameter('orden', $orden)
	    		  ->setParameter('eliminado', false)
		          ->addOrderBy('cat.grupo')
		          ->addOrderBy('art.codigoInterno')
		          ->getQuery()
		          ->getResult(); 
	} 

    public function getItemsOrdenCarga(\GestionFaenaBundle\Entity\faena\OrdenCarga $orden)
    {
        return $this->getEntityManager()
            		->createQuery('SELECT it, SUM(it.cantidad) as cantidad 
                                   FROM GestionFaenaBundle:faena\ItemCarga it 
                                   JOIN it.articulo art
                                   JOIN it.comprobante comp
                                   JOIN art.categoria cat
                               	   WHERE comp.eliminado = :eliminado AND comp.ordenCarga = :orden 
                               	   GROUP BY art.id
                               	   ORDER BY cat.grupo, art.codigoInterno')
				    ->setParameter('orden', $orden)
	    		    ->setParameter('eliminado', false)
            		->getResult();
    }
}
