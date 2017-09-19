<?php
/**
 * Created by PhpStorm.
 * User: Isuru
 * Date: 8/11/2016
 * Time: 5:46 PM
 */

namespace AppBundle\RepositoryORM;

use Doctrine\ORM\EntityRepository;
class Base extends EntityRepository
{

    /**
     * retuns a new instance of an entity
     */
    public function getNewEntity($class=null)
    {
        $object = '';
        $pathComponents = explode('\\',$this->_entityName);
        $pathStringHalf = '';
            foreach($pathComponents as $index=>$pathComponent){
                if(count($pathComponents)-1 > $index)
                    $pathStringHalf = $pathStringHalf.'\\'.$pathComponent;
            }
        if($class == null)
            $object = '\\'.$this->_entityName;
        else
            $object = '\\'.$pathStringHalf.$class;
        return new $object();
    }

    /**
     * get the current date/time
     * @return string
     */
    protected function getCurrentDateTime()
    {
        $dateTime = new \DateTime('now',new \DateTimeZone('Asia/Colombo'));
        return $dateTime;
    }

    /**
     * Repository Shortcut
     * @param $class
     */
    protected function getRepository($class=null)
    {
        $pathComponents = explode('\\',$this->_entityName);
        $className = $pathComponents[count($pathComponents)-1];
        return $this->getEntityManager()->getRepository('AppBundle:'.($class!=null ? $class:$className));
    }

    /**
     * QueryBuilder shortcut
     * @param $class
     * @return mixed
     */
    protected function getQueryBuilder($class=null){
        $pathComponents = explode('\\',$this->_entityName);
        $className = $pathComponents[count($pathComponents)-1];
        return $this->getEntityManager()->createQueryBuilder('AppBundle:'.($class!=null ? $class:$className));
    }

    /**
     * generalized method for backend searches
     * @param $searchForm
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function backendListSearch($searchForm,$request){
        $searchForm->handleRequest($request);
        $qb = $this->createQueryBuilder('object');
        $allFields = $searchForm->all();
        foreach($allFields as $fieldName=>$fieldValue){
            $field = $searchForm->get($fieldName)->getData();
            if($field != '' && $field !=null){
         //   $qb->andWhere('object.'.$fieldName.' = :'.$fieldName)
               $qb->andWhere('object.'.$fieldName.' LIKE :'.$fieldName)
                    //->setParameter($fieldName,$field);
                    ->setParameter($fieldName,'%'.$field.'%');
            }
        }
        $qb->orderBy('object.id','DESC');
        return $qb;
    }
}
