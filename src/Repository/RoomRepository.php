<?php

namespace App\Repository;

use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\SqlResultSetMapping;
use Doctrine\ORM\Query\ResultSetMapping;
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
        $time1 = strtotime('$dateFirst');
        $time2 = strtotime('$dateSecond');

        $rsm = new ResultSetMapping();
        $rsm->addEntityResult('App\Entity\Room', 'r');
        $rsm->addFieldResult('r','id','id');
        $rsm->addFieldResult('r','title','title');
        $rsm->addJoinedEntityResult('App\Entity\Reservation','res', 'r','reservations');
        $rsm->addFieldResult('res','date1','date1');
        $rsm->addFieldResult('res','date2','date2');



        $query = $this->getEntityManager()->createNativeQuery('SELECT room.id, room.title FROM room
        WHERE room.id NOT IN(SELECT room_id FROM reservation_room JOIN reservation ON reservation_room.reservation_id=reservation.id 
        WHERE ? < date2 AND ? > date1)', $rsm);
        $query->setParameter(1,$dateFirst);
        $query->setParameter(2,$dateSecond);


        return $query->getResult();

    }
}
