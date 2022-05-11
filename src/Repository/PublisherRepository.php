<?php

namespace App\Repository;

use App\Entity\Publisher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Publisher|null find($id, $lockMode = null, $lockVersion = null)
 * @method Publisher|null findOneBy(array $criteria, array $orderBy = null)
 * @method Publisher[]    findAll()
 * @method Publisher[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PublisherRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Publisher::class);
    }

    public function add(Publisher $publisher, bool $flush = true): void {
        $this->_em->persist($publisher);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(Publisher $publisher, bool $flush = true): void {
        $this->_em->remove($publisher);
        if ($flush) {
            $this->_em->flush();
        }
    }


    /**
     * @return Publisher[]
     */
    public function getPublishersAll(): array {
        return $this->createQueryBuilder('p')
            ->select('p', 'country', 'games')
            ->join('p.country', 'country')
            // Pour afficher les editeurs qui on 0 jeux (null) il faut faire un leftJoin
            ->join('p.games', 'games')
            ->orderBy('p.name')
//            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getPublisher($slug) {
        return $this->createQueryBuilder('p')
            ->select('p', 'country', 'games')
            ->leftJoin('p.country', 'country')
            ->leftJoin('p.games', 'games')
            ->where('p.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    // Mise en place de la pagination => requête select * sur l'entité  ==> utiliser dans le controller publisherAdminController pour la liste des tous les publishers
    public function getQbAll(){
        return $this->createQueryBuilder('p')
            -> orderBy('p.name')
        ;
    }



}
