<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 8/12/16
 * Time: 10:16 AM
 */

namespace AppBundle\RepositoryORM;

class CommonUser extends Base
{
    public function register($request){
        $commonUserRoleRepo = $this->getRepository('CommonUserRole');
        $commonUserRole = $commonUserRoleRepo->findOneBy(array('code'=>'general'));
        $object=$this->getNewEntity();
        $object->setName($request->name)
            ->setEmail($request->email)
            ->setContact($request->phone)
            ->setUsername($request->username)
            ->setPassword($request->password)
            ->setCommonUserRole($commonUserRole)
            ->setSubscriberId('foo-bar') //TODO get subscriber id from Dialog MIFE
            ;
        $em = $this->getEntityManager();
        $em->persist($object);
        $em->flush();
    }
    public function profile($id){
        $qb = $this->createQueryBuilder('p');
        $qb->where('p.id = :id')
            ->setParameter('id', $id)
            ;
        $result = $qb->getQuery()->getSingleResult();
        return $result;
    }

    public function getUserFromEmail($email){
        $qb = $this->createQueryBuilder('p');
        $qb->where('p.email = :email')
            ->setParameter('email', $email)
        ;
        $result = $qb->getQuery()->getSingleResult();
        return $result;
    }
    public function profileFromEmail($emailConfirmCode){
        $qb = $this->createQueryBuilder('p');
        $qb->where('p.emailConfirmCode = :emailConfirmCode')
            ->setParameter('emailConfirmCode', $emailConfirmCode)
        ;
        $result = $qb->getQuery()->getSingleResult();
        return $result;
    }

    public function checkForUserName($request){
        $username = $request->username;
        $qb = $this->createQueryBuilder('object');
        $qb->where('object.status=1')
            ->andWhere('object.username = :givenUsername')
            ->setParameter('givenUsername', $username);
        $qb->orderBy('object.id','DESC');
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function checkForContact($request){
        $contact = $request->phone;
        $qb = $this->createQueryBuilder('object');
        $qb->where('object.status=1')
            ->andWhere('object.contact = :givenContact')
            ->setParameter('givenContact', $contact);
        $qb->orderBy('object.id','DESC');
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function checkForEmail($request){
        $email = $request->email;
        $qb = $this->createQueryBuilder('object');
        $qb->where('object.status=1')
            ->andWhere('object.email = :givenEmail')
            ->setParameter('givenEmail', $email);
        $qb->orderBy('object.id','DESC');
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getAllNos(){
        $qb = $this->createQueryBuilder('object');
        $qb->select('object.contact');
        $qb->where('object.status=1');
        $qb->orderBy('object.id','DESC');
        $result = $qb->getQuery()->getResult();
        $noArray = array();
        foreach ($result as $eachResult){
            foreach ($eachResult as $key=>$value){
                $noArray[] = $value;
            }
        }
        return $noArray;
    }

    public function getFreeNos(){
        $qb = $this->createQueryBuilder('object');
        $qb->select('object.contact');
        $qb->leftJoin('object.commonUserPackage','commonUserPackage')
            ->where('object.status=1')
            ->andWhere('commonUserPackage.name= :Free')
            ->setParameter('Free','Free');
        $qb->orderBy('object.id','DESC');
        $result = $qb->getQuery()->getResult();
        $noArray = array();
        foreach ($result as $eachResult){
            foreach ($eachResult as $key=>$value){
                $noArray[] = $value;
            }
        }
        return $noArray;
    }

    public function getAllPackageNos(){
        $qb = $this->createQueryBuilder('object');
        $qb->select('object.contact');
        $qb->leftJoin('object.commonUserPackage','commonUserPackage')
            ->where('object.status=1')
            ->andWhere('commonUserPackage.name!= :Free')
            ->setParameter('Free','Free');
        $qb->orderBy('object.id','DESC');
        $result = $qb->getQuery()->getResult();
        $noArray = array();
        foreach ($result as $eachResult){
            foreach ($eachResult as $key=>$value){
                $noArray[] = $value;
            }
        }
        return $noArray;
    }

    public function getPackageANos(){
        $qb = $this->createQueryBuilder('object');
        $qb->select('object.contact');
        $qb->leftJoin('object.commonUserPackage','commonUserPackage')
            ->where('object.status=1')
            ->andWhere('commonUserPackage.name = :PackageA')
            ->setParameter('PackageA','Package A');
        $qb->orderBy('object.id','DESC');
        $result = $qb->getQuery()->getResult();
        $noArray = array();
        foreach ($result as $eachResult){
            foreach ($eachResult as $key=>$value){
                $noArray[] = $value;
            }
        }
        return $noArray;
    }

    //Same Functions should be created for Packkage B,C,D etc(Or whatever the Packages they give)

    public function getUsersForGivenRoute($route){
        $qb = $this->createQueryBuilder('object');
        $qb->select('object');
        $qb->where('object.status=1')
            ->andWhere('object.route = :route')
            ->setParameter('route',$route);
        $qb->orderBy('object.id','DESC');
        $result = $qb->getQuery()->getResult();

        return $result;
    }

    //returns users ahead of the given node(location of the truck)
    public function getUsersForGivenRouteAhead($route,$truckNodeIndex){
        $qb = $this->createQueryBuilder('object');
        $qb->select('object');
        $qb->where('object.status=1')
            ->andWhere('object.route = :route')
            ->andWhere('object.collectPointHashId > :truckindex')
            ->setParameter('route',$route)
            ->setParameter('truckindex',$truckNodeIndex);
        $qb->orderBy('object.id','DESC');
        $result = $qb->getQuery()->getResult();

        return $result;
    }

    public function getUserForEmail($email){
        $qb = $this->createQueryBuilder('p');
        $qb->where('p.email = :email')
            ->setParameter('email', $email)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

}