<?php

namespace App\Repository;

use App\Classe\Search;
use App\Entity\Recette;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recette>
 *
 * @method Recette|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recette|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recette[]    findAll()
 * @method Recette[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecetteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recette::class);
    }

    public function add(Recette $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Recette $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findWithSearch(Search $search)
    {
        $query = $this
            ->createQueryBuilder('r')
            ->select('c','r')
            ->join('r.category', 'c');

        if(!empty($search->categories)){
            $query = $query
                ->andWhere('c.id IN (:categories)')
                ->setParameter( 'categories', $search->categories);
        }

        if(!empty($search->string)){
            $query = $query
                ->andWhere('r.name LIKE :string')
                ->setParameter( 'string',"%{$search->string}%");
        }

        return $query->getQuery()->getResult();
    }


    /**
     * @return Recette[] Returns an array of Recette objects
    */
   public function findByRecette(Search $search): array
    {
        $query = $this
            ->createQueryBuilder('r')
            ->select('c','r')
            ->join('r.category', 'c');

        if(!empty($search->categories)){
            $query = $query
                ->andWhere('c.id IN (:categories)')
                ->setParameter( 'categories', $search->categories);
        }

        if(!empty($search->string)){
            $query = $query
                ->andWhere('r.name LIKE :string')
                ->setParameter( 'string',"%{$search->string}%");
        }

         return $query->getQuery()->getResult();

   }

//    public function findOneBySomeField($value): ?Recette
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
