<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Entity\Film;
use App\Entity\Seance;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Film>
 *
 * @method Film|null find($id, $lockMode = null, $lockVersion = null)
 * @method Film|null findOneBy(array $criteria, array $orderBy = null)
 * @method Film[]    findAll()
 * @method Film[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Film::class);
    }

    /**
     * @return Film[] Returns an array of Film objects
     */
    public function findAllFilmsAffiches(): array
    {
        return $this->createQueryBuilder('film')
            ->select('DISTINCT film')
            ->from('App\Entity\Film', 'f')
            ->innerJoin('App\Entity\Seance', 's', 'WITH', 's.film = film')
            ->andWhere('s.dateProjection  >= :now')
            ->setParameter('now', new \DateTime())
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return Film[] Returns an array of Film objects
     */
    public function findFilmDateProjectionAsc($id) :array
    {
        return $this->createQueryBuilder('film')
            ->select('DISTINCT film')
            ->from('App\Entity\Film', 'f')
            ->innerJoin('App\Entity\Seance', 's', 'WITH', 's.film = film')
            ->andWhere('s.dateProjection  >= :now')
            ->addOrderBy('s.dateProjection', 'ASC')
             ->setParameter('now', new \DateTime())
            ->getQuery()
            ->getResult()
            ;
    }



//    /**
//     * @return Film[] Returns an array of Film objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Film
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
