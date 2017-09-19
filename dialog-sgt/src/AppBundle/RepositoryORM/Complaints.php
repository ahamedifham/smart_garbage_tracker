<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 11/9/16
 * Time: 5:06 AM
 */

namespace AppBundle\RepositoryORM;


class Complaints extends Base
{
    public function register($request){

        $date = new \DateTime($request->date);
        $time = new \DateTime($request->time);


        $commonUserRepo = $this->getRepository('CommonUser');
        $user= $commonUserRepo->profile($request->userId);

        $object=$this->getNewEntity();
        $object->setDescription($request->description)
            ->setDate($date)
            ->setTime($time)
            ->setCommonUser($user)
        ;
        $em = $this->getEntityManager();
        $em->persist($object);
        $em->flush();
    }

    public function getTodayComplaints($date){
        //$originalDate = new \DateTime($date);
        $qb = $this->createQueryBuilder('p');
        $qb->where('p.date = :date')
            ->setParameter('date', $date)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getComplaintsFromUser($user){
        $qb = $this->createQueryBuilder('p');
        $qb->where('p.commonUser = :user')
            ->setParameter('user', $user)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }
}