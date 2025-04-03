<?php

namespace App\Repository;

use App\Entity\Paper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Paper>
 */
class PaperRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Paper::class);
    }

    /**
     * Finds the IDs of all papers with the specified anchor.
     *
     * @param string $anchor
     * @return int[] Returns an array of Paper IDs
     */
    public function findIdsByAnchor(string $anchor): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.id')
            ->andWhere('p.anchor = :anchor')
            ->setParameter('anchor', $anchor)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getSingleColumnResult();
    }

    /**
     * Finds a list of distinct Tag objects associated with the given paper IDs.
     *
     * @param int[] $paperIds An array of paper IDs
     * @return Tag[] Returns an array of distinct Tag objects
     */
    public function findDistinctTagsByPaperIds(array $paperIds): array
    {
        return $this->createQueryBuilder('p')
            ->select('t.id, t.name')
            ->innerJoin('p.tags', 't')
            ->where('p.id IN (:paperIds)')
            ->setParameter('paperIds', $paperIds)
            ->groupBy('t.id')
            ->getQuery()
            ->getResult();
    }
}
