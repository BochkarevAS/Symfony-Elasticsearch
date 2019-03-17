<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\DTO\CatalogDto;

class CatalogElasticaRepository extends EntityRepository
{
    public function createIsActiveQueryBuilder()
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.id', 'ASC');
    }

    public function search(CatalogDto $catalog)
    {
        $qb   = new \Elastica\QueryBuilder();
        $bool = $qb->query()->bool();

        if (!empty($catalog->name)) {
            $bool->addMust($qb->query()->bool()->addMust($qb->query()->match('name', $catalog->name)));
        }

        if (null !== $catalog->brand) {
            $bool->addMust($qb->query()->bool()->addMust($qb->query()->term(['brand.id' => $catalog->brand->getId()])));
        }

        if (!empty($catalog->marks)) {

            $bool->addMust($qb->query()->bool()->addMust(
                $qb->query()->nested()->setPath('marks')->setQuery($qb->query()->bool()->addMust($qb->query()->term(['marks.id' => $catalog->marks->getId()])))
            ));
        }
    }
}