<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\DTO\ProductDto;

class CatalogElasticaRepository extends EntityRepository
{
    public function createIsActiveQueryBuilder()
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.id', 'ASC');
    }

    public function search(ProductDto $product)
    {
        $qb   = new \Elastica\QueryBuilder();
        $bool = $qb->query()->bool();

        if (!empty($product->name)) {
            $bool->addMust($qb->query()->bool()->addMust($qb->query()->match('name', $product->name)));
        }

        if (null !== $product->brand) {
            $bool->addMust($qb->query()->bool()->addMust($qb->query()->term(['brand.id' => $product->brand->getId()])));
        }

        if (!empty($product->marks)) {

            $bool->addMust($qb->query()->bool()->addMust(
                $qb->query()->nested()->setPath('marks')->setQuery($qb->query()->bool()->addMust($qb->query()->term(['marks.id' => $product->marks->getId()])))
            ));
        }
    }
}