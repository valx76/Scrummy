<?php

namespace App\Repository;

use App\Entity\RoomChoice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RoomChoice>
 *
 * @method RoomChoice|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoomChoice|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoomChoice[]    findAll()
 * @method RoomChoice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomChoiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoomChoice::class);
    }

    public function save(RoomChoice $roomChoice): int
    {
        $this->getEntityManager()->persist($roomChoice);
        $this->getEntityManager()->flush();

        return $roomChoice->getId();
    }
}
