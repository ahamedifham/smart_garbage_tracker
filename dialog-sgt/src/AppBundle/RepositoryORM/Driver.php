<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 8/12/16
 * Time: 10:03 AM
 */

namespace AppBundle\RepositoryORM;


class Driver extends Base
{
    public function profile($id){
        $qb = $this->createQueryBuilder('p');
        $qb->where('p.id = :id')
            ->setParameter('id', $id)
        ;
        $result = $qb->getQuery()->getSingleResult();
        return $result;
    }

    public function getDriverFromContact($contact){
        $qb = $this->createQueryBuilder('p');
        $qb->where('p.contact = :contact')
            ->setParameter('contact', $contact)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }
}