<?php

namespace App\Repository;

use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Node\Stmt\Expression;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method Room|null find($id, $lockMode = null, $lockVersion = null)
 * @method Room|null findOneBy(array $criteria, array $orderBy = null)
 * @method Room[]    findAll()
 * @method Room[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Room::class);
        $this->entitymanager = $entityManager;
    }

    // /**
    //  * @return Room[] Returns an array of Room objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Room
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findAllAvailableRooms($dateFirst,$dateSecond){
        $conn = $this->getEntityManager();
        $sql = 'SELECT * FROM room
        WHERE room.id NOT IN(SELECT room_id FROM reservation_room JOIN reservation ON reservation_room.reservation_id=reservation.id 
        WHERE :dateFirst < date2 AND :dateSecond > date1)';
        $stmt = $conn->getConnection()->prepare($sql);
        $stmt->execute(['dateFirst' => $dateFirst, 'dateSecond' => $dateSecond]);
        return $stmt->fetchAll();

    }
}
