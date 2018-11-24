<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class DockerfileConfigRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function findAllAsArray()
    {
        $qb = $this->createQueryBuilder('dc');
        $qb->select('dc.id', 'dc.name', 'dc.baseImage', 'dc.description');

        return $qb->getQuery()->getArrayResult();
    }
}
