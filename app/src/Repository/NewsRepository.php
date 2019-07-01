<?php

namespace App\Repository;

use App\Criteria\NewsCriteria;
use App\Entity\News;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Type;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method News|null find($id, $lockMode = null, $lockVersion = null)
 * @method News|null findOneBy(array $criteria, array $orderBy = null)
 * @method News[]    findAll()
 * @method News[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, News::class);
    }

    /**
     * @param NewsCriteria $criteria
     * @return News[]
     */
    public function getList(NewsCriteria $criteria)
    {
        $queryBuilder = $this->createQueryBuilder('t');

        if ($criteria->getCount() && $criteria->getCount() > 0) {
            $queryBuilder->setMaxResults($criteria->getCount());
        }

        if ($criteria->getOffset() && $criteria->getOffset() >= 0) {
            $queryBuilder->setFirstResult($criteria->getOffset());
        }

        if ($criteria->getCategory()) {
            $queryBuilder->andWhere('t.category = :category')
                ->setParameter('category', $criteria->getCategory());
        }

        if ($criteria->getDateFrom()) {
            $queryBuilder->andWhere('t.createdAt >= :date_from')
                ->setParameter('date_from', $criteria->getDateFrom(), Type::DATETIME);
        }

        if ($criteria->getDateTo()) {
            $queryBuilder->andWhere('t.createdAt <= :date_to')
                ->setParameter('date_to', $criteria->getDateTo(), Type::DATETIME);
        }

        $queryBuilder->orderBy('t.id', 'DESC');

        return $queryBuilder->getQuery()->getResult();
    }

    public function save(News $news)
    {
        $this->getEntityManager()->persist($news);
        $this->getEntityManager()->flush($news);
    }

    public function merge(News $news)
    {
        return $this->getEntityManager()->merge($news);
    }

    public function remove(News $news)
    {
        $this->getEntityManager()->remove($news);
        $this->getEntityManager()->flush($news);
    }
}
