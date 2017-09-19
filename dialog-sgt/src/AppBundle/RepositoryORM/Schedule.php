<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 10/21/16
 * Time: 5:39 PM
 */

namespace AppBundle\RepositoryORM;


use Doctrine\DBAL\Tools\Console\Command\ReservedWordsCommand;
use Symfony\Component\HttpFoundation\Request;

class Schedule extends Base
{
    /**
     * generalized method for backend searches
     * @param $searchForm
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function backendListSearch($searchForm,$request){
        $searchForm->handleRequest($request);
        $date = $searchForm->get('date')->getData();

        $qb = $this->createQueryBuilder('object');

        if($date){
            $day= date('l', strtotime( $date->format('Y-m-d')));
            $qb->select('object');

            $qb->leftJoin('object.weekday', 'w')
                ->where('w.dayName = :dayName')
                ->andWhere('object.date <= :findDate')
                ->andWhere('object.repeat1 = :ok')
                ->orWhere($qb->expr()->andX(
                    $qb->expr()->eq('object.repeat1' , ':notOk'),
                    $qb->expr()->eq('object.date', ':findDate')
                ))
                ->setParameter('dayName',$day)
                ->setParameter('findDate',$date)
                ->setParameter('notOk' , false)
                ->setParameter('ok', true);

            $qb->orderBy('object.id','DESC');
            $result = $qb->getQuery()->getResult();
//            var_dump($qb);
//            exit();
            return $qb;
//            var_dump($result);
//            exit();
            //return $result;

        } else{
            $allFields = $searchForm->all();
            foreach($allFields as $fieldName=>$fieldValue){
                $field = $searchForm->get($fieldName)->getData();
                if($field != '' && $field !=null){
                    $qb->andWhere('object.'.$fieldName.' = :'.$fieldName)
                        ->setParameter($fieldName,$field);
                }
            }
//        exit();
            $qb->orderBy('object.id','DESC');
            return $qb;
        }


//        $qb->select('object.name')
//            ->orWhere($qb->expr()->andX(
//                $qb->expr()->eq('object.repeat1' , ':notOk'),
//                $qb->expr()->eq('object.date', ':findDate')
//            ))
////            ->where('object.repeat1 = :notOk')
////            ->andwhere('object.date = :findDate')
//            ->setParameter('findDate',$date)
//            ->setParameter('notOk' , false);


//        var_dump($qb->getQuery()->getResult());
//        var_dump($qb->getQuery());
//        exit();


    }

    public function getSchedules($date){
        $qb = $this->createQueryBuilder('object');
        $day= date('l', strtotime( $date));
        $qb->select('object');

        $qb->leftJoin('object.weekday', 'w')
            ->where('w.dayName = :dayName')
            ->andWhere('object.date <= :findDate')
            ->andWhere('object.repeat1 = :ok')
            ->orWhere($qb->expr()->andX(
                $qb->expr()->eq('object.repeat1' , ':notOk'),
                $qb->expr()->eq('object.date', ':findDate')
            ))
            ->setParameter('dayName',$day)
            ->setParameter('findDate',$date)
            ->setParameter('notOk' , false)
            ->setParameter('ok', true);

        $qb->orderBy('object.id','DESC');
        $result = $qb->getQuery()->getResult();
//            var_dump($qb);
//            exit();
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
    
    public function getSchedulesWithDrivers($date, $driver){
        $qb = $this->createQueryBuilder('object');
        $day= date('l', strtotime( $date));
        $qb->select('object');

        $qb->leftJoin('object.weekday', 'w')
            ->where('w.dayName = :dayName')
            ->andWhere('object.date <= :findDate')
            ->andWhere('object.repeat1 = :ok')
            ->andWhere('object.driver = :driver')
            ->orWhere($qb->expr()->andX(
                $qb->expr()->eq('object.repeat1' , ':notOk'),
                $qb->expr()->eq('object.date', ':findDate'),
                $qb->expr()->eq('object.driver', ':driver')
            ))
            ->setParameter('dayName',$day)
            ->setParameter('findDate',$date)
            ->setParameter('notOk' , false)
            ->setParameter('driver' , $driver)
            ->setParameter('ok', true);

        $qb->orderBy('object.id','DESC');
        $result = $qb->getQuery()->getResult();
//            var_dump($qb);
//            exit();
        return $result;
    }

    public function getSchedulesWithRoute($date, $route){
        $qb = $this->createQueryBuilder('object');
        $day= date('l', strtotime( $date));
        $qb->select('object');

        $qb->leftJoin('object.weekday', 'w')
            ->where('w.dayName = :dayName')
            ->andWhere('object.date <= :findDate')
            ->andWhere('object.repeat1 = :ok')
            ->andWhere('object.route = :route')
            ->orWhere($qb->expr()->andX(
                $qb->expr()->eq('object.repeat1' , ':notOk'),
                $qb->expr()->eq('object.date', ':findDate'),
                $qb->expr()->eq('object.route', ':route')
            ))
            ->setParameter('dayName',$day)
            ->setParameter('findDate',$date)
            ->setParameter('notOk' , false)
            ->setParameter('route' , $route)
            ->setParameter('ok', true);

        $qb->orderBy('object.id','DESC');
        $result = $qb->getQuery()->getResult();
//            var_dump($qb);
//            exit();
        return $result;
    }

    public function getSchedulesWithTrackingUnit($date, $trackingUnit){
        $qb = $this->createQueryBuilder('object');
        $day= date('l', strtotime( $date));
        $qb->select('object');

        $qb->leftJoin('object.weekday', 'w')
            ->where('w.dayName = :dayName')
            ->andWhere('object.date <= :findDate')
            ->andWhere('object.repeat1 = :ok')
            ->andWhere('object.truck = :truck')
            ->orWhere($qb->expr()->andX(
                $qb->expr()->eq('object.repeat1' , ':notOk'),
                $qb->expr()->eq('object.date', ':findDate'),
                $qb->expr()->eq('object.truck', ':truck')
            ))
            ->setParameter('dayName',$day)
            ->setParameter('findDate',$date)
            ->setParameter('notOk' , false)
            ->setParameter('truck' , $trackingUnit)
            ->setParameter('ok', true);

        $qb->orderBy('object.id','DESC');
        $result = $qb->getQuery()->getResult();
        return $result;

    }
}