<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Like;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class LikesRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Like::class);
    }

    public function save(Like $like): void
    {
        $this->getEntityManager()->persist($like);
        $this->getEntityManager()->flush();
    }

    public function getTotalLikesForUserId(int $userId): int
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('count(l.userId)')
            ->from('App:Like', 'l')
            ->where('l.userId = ?1')
            ->setParameter(1, $userId);

        return (int) $qb->getQuery()->getSingleScalarResult();
    }
}
