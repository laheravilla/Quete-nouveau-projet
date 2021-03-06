<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function findAllWithCategoriesAndTags()
    {
//        $entityManager = $this->getEntityManager();
//        $query = $entityManager->createQuery(
//            'SELECT a, c, t
//            FROM App\Entity\Article a
//            LEFT JOIN a.category c
//            LEFT JOIN a.tags t
//        );
//
//        return $query->execute();

        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.category', 'c' )
            ->leftJoin('a.tags', 't' )
            ->addSelect('c', 'a', 't')
            ->getQuery();

        return $qb->execute();
    }
}
