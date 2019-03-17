<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Internal\Hydration\IterableResult;

class CatalogRepository extends EntityRepository
{
    public function createSearchQueryBuilder($entityAlias)
    {
        return $this->createQueryBuilder($entityAlias)
            ->select($entityAlias .', b, m')
            ->leftJoin($entityAlias . '.brand', 'b')
            ->leftJoin($entityAlias . '.marks', 'm');
    }

    /**
     * @return IterableResult
     **/
    public function remove(int $id)
    {
        $dql = "
            SELECT p 
            FROM App\Entity\Catalog c
            WHERE c.id=:id
        ";

        return $this->getEntityManager()
            ->createQuery($dql)
            ->setParameter('id', $id)
            ->iterate();
    }
}