<?php

namespace App\Repository;

use App\Entity\EdgeRunnerDownTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EdgeRunnerDownTime>
 */
class EdgeRunnerDownTimeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EdgeRunnerDownTime::class);
    }
}
