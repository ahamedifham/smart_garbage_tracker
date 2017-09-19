<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 8/22/16
 * Time: 6:07 PM
 */

namespace AppBundle\RepositoryORM;


class Route extends Base
{
    public function count(){
        $qb = $this->createQueryBuilder('p');
        $qb->select('count(p.id)');
        $result = $qb->getQuery()->getSingleScalarResult();
        return $result;
    }

    public function returnRoute($routeId){
        $qb = $this->createQueryBuilder('p');
        $qb->where('p.routeId = :routeId')
            ->setParameter('routeId', $routeId);
        $result = $qb->getQuery()->getSingleResult();
        return $result;
    }

    public function profile($id){
        $qb = $this->createQueryBuilder('p');
        $qb->where('p.id = :id')
            ->setParameter('id', $id)
        ;
        $result = $qb->getQuery()->getSingleResult();
        return $result;
    }
}