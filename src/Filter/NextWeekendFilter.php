<?php

namespace App\Filter;

use ApiPlatform\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\QueryBuilder;

class NextWeekendFilter extends AbstractFilter
{
    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []): void
    {
        if ($property !== 'nextWeekend') {
            return;
        }

        $queryNameGenerator->generateParameterName($property);

        $queryBuilder
            ->andWhere('o.happensAt >= :weekendStart')
            ->andWhere('o.happensAt <= :weekendEnd')
            ->setParameter('weekendStart', new \DateTime('next saturday 00:00:00'))
            ->setParameter('weekendEnd', new \DateTime('next sunday 23:59:59'));
    }

    public function getDescription(string $resourceClass): array
    {
        return [
            'nextWeekend' => [
                'property' => 'nextWeekend',
                'type' => 'boolean',
                'required' => false,
                'swagger' => [
                    'description' => 'Filter encounters for the next weekend',
                    'name' => 'Next Weekend',
                    'type' => 'boolean',
                ],
            ],
        ];
    }
}