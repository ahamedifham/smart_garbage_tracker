<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 9/21/16
 * Time: 11:13 AM
 */

namespace AppBundle\RepositoryDOM;
use Doctrine\ODM\MongoDB\DocumentRepository;


class RouteInfoRepository extends DocumentRepository
{
    /**
     * @param string $field
     * @param string $data
     * @return array|null|object
     */
    public function findOneByProperty($field, $data){
        return
        $this->createQueryBuilder('RouteInfo')
            ->field($field)->equals($data)
            ->getQuery()
            ->getSingleResult();
    }
}