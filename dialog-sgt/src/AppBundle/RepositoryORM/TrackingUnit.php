<?php
/**
 * Created by PhpStorm.
 * User: Isuru
 * Date: 8/11/2016
 * Time: 6:33 PM
 */

namespace AppBundle\RepositoryORM;


class TrackingUnit extends Base
{
    public function routeIdGet($id){
        $qb = $this->createQueryBuilder('p');
        $qb->where('p.id = :id')
            ->setParameter('id', $id)
        ;
        $result = $qb->getQuery()->getSingleResult();
        return $result;
    }

    public function getUnit($msisdn){
        $qb = $this->createQueryBuilder('p');
        $qb->where('p.msisdn = :msisdn')
            ->setParameter('msisdn', $msisdn)
        ;
        $result = $qb->getQuery()->getSingleResult();
        return $result;
    }

    public function getTrackingUnitFromRoute($route){
        $qb = $this->createQueryBuilder('p');
        $qb->where('p.route = :route')
            ->setParameter('route', $route)
        ;
        $result = $qb->getQuery()->getSingleResult();
        return $result;
    }

    public function getTrackingUnitFromMsisdn($msisdn){
        $qb = $this->createQueryBuilder('p');
        $qb->where('p.msisdn = :msisdn')
            ->setParameter('msisdn', $msisdn)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

}
