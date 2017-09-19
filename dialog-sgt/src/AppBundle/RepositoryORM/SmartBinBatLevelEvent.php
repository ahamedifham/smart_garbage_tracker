<?php

namespace AppBundle\RepositoryORM;

use Doctrine\ORM\EntityRepository;

/**
 * SmartBinBatLevelEvent
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SmartBinBatLevelEvent extends Base
{
    public function profile($event){
        $qb = $this->createQueryBuilder('p');
        $qb->where('p.event = :event')
            ->setParameter('event', $event)
        ;
        $result = $qb->getQuery()->getSingleResult();
        return $result;
    }
}
