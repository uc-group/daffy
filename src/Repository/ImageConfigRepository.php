<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class ImageConfigRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function findAllAsArray()
    {
        $qb = $this->createQueryBuilder('ic');
        $qb->select('ic.id');

        return array_column($qb->getQuery()->getArrayResult(), 'id');
    }
}
