<?php

namespace AppBundle\Repository;

/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleRepository extends \Doctrine\ORM\EntityRepository
{
  public function derniersArticles($nbr)
  {
    $qb = $this->createQueryBuilder('a');

    $offset = 0;
    $limit = $nbr;
    $qb->select('a')
       ->where('a.archived = FALSE AND a.publited = TRUE')
       ->orderBy('a.datePublished', 'DESC')
       ->setFirstResult( $offset )
       ->setMaxResults( $limit )
    ;
    return $qb
      ->getQuery()
      ->getResult()
      // ->getResult()
    ;
  }

  public function findNoneArchived()
  {
    $qb = $this->createQueryBuilder('a');

    $qb->select('a')
       ->where('a.archived = FALSE')
       ->orderBy('a.id', 'DESC')
    ;
    return $qb
      ->getQuery()
      ->getResult()
      // ->getResult()
    ;
  }
}
