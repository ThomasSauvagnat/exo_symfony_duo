<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function add(Comment $comment, bool $flush = true): void {
        $this->_em->persist($comment);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(Comment $comment, bool $flush = true): void {
        $this->_em->remove($comment);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function lastComments()
    {
        return $this -> createQueryBuilder('c')
        -> innerJoin('c.account', 'a')
        -> join('c.game', 'g')
        -> orderBy('c.createdAt', 'DESC')
        -> setMaxResults(4)
        -> getQuery() -> getResult();
    }
    // Requete pour récupérer les commentaires d'un utilisateur en fonction d'un jeu
    public function findOneByGameAndUser($gameEntity, $user)
    {
        return $this->createQueryBuilder('c')
            ->join('c.game', 'g')
            ->join('c.account', 'a')
            ->where('g = :game')
            ->andWhere('a = :account')
            ->setParameter('game', $gameEntity)
            ->setParameter('account', $user)
            // Quand on passe par un getOneOrNullResult() mettre un setMaxResults(1) pour éviter le fait que l'on renvoi plus d'un seul résultat
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();
    }
}
