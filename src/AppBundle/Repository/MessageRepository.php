<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Message;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;

class MessageRepository extends \Doctrine\ORM\EntityRepository
{

  public function findByReadAt()
  {
    return $this->createQueryBuilder('m')
      ->andWhere('m.read_at IS NULL')
      ->orderBy('m.id', 'ASC')
      ->getQuery()
      ->getResult()
    ;
  }


  public function myFindAll()
  {
    return $this->createQueryBuilder('m')
      ->orderBy('m.id', 'DESC')
      ->getQuery()
    ;
  }

}
