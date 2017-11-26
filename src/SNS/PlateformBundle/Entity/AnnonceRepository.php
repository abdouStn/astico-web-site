<?php

namespace SNS\PlateformBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;


/**
 * AnnonceRepository
 *
 *
 */
class AnnonceRepository extends EntityRepository
{
	public function getAnnonces($page, $nbPerPage)
	{
	    $query = $this->createQueryBuilder('a')
		->leftJoin('a.auteur', 'au')
		->addSelect('au')
		->orderBy('a.date', 'DESC')
		->getQuery()
	    ;

	    $query
		// On définit l'annonce à partir de laquelle commencer la liste
		->setFirstResult(($page-1) * $nbPerPage)
		// Ainsi que le nombre d'annonce à afficher sur une page
		->setMaxResults($nbPerPage)
	    ;

	    // Enfin, on retourne l'objet Paginator correspondant à la requête construite
	    // (n'oubliez pas le use correspondant en début de fichier)
	    return new Paginator($query, true);
	}
	

}

