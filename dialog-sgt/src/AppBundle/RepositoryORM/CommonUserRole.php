<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 10/31/16
 * Time: 10:41 AM
 */

namespace AppBundle\RepositoryORM;

use Doctrine\ORM\EntityRepository;

class CommonUserRole extends Base
{
    public function profile($id){
        $qb = $this->createQueryBuilder('p');
        $qb->where('p.id = :id')
            ->setParameter('id', $id)
        ;
        $result = $qb->getQuery()->getSingleResult();
        return $result;
    }

    public function count(){
        $qb = $this->createQueryBuilder('p');
        $qb->select('count(p.id)');
        $result = $qb->getQuery()->getSingleScalarResult();
        return $result;
    }

    
}