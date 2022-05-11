<?php

namespace App\Repository;

use App\Entity\Account;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Account|null find($id, $lockMode = null, $lockVersion = null)
 * @method Account|null findOneBy(array $criteria, array $orderBy = null)
 * @method Account[]    findAll()
 * @method Account[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Account::class);
    }

    public function add(Account $account, bool $flush = true): void {
        $this->_em->persist($account);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(Account $account, bool $flush = true): void {
        $this->_em->remove($account);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getAccountDetails($name) {
        return $this -> createQueryBuilder('a')
        -> select('a', 'l', 'g', 'c')
        -> join('a.libraries', 'l')
        -> join('l.game', 'g')
        -> leftjoin('a.comments', 'c')
        -> where('a.name = :name')
        -> setParameter('name', $name)
        -> getQuery() -> getOneOrNullResult();
    }

    public function getTotalGameTime($name)
    {
        return $this -> createQueryBuilder('a')
        -> select('SUM(l.gameTime)')
        -> join('a.libraries', 'l')
        -> where('a.name = :name')
        -> setParameter('name', $name)
        -> getQuery() -> getOneOrNullResult();
    }



    // ######### Pagination
//    public function getQball(){
//        return $this->createQueryBuilder('a');
//    }


    public function getQbAll()
    {
        return $this -> createQueryBuilder('a');
    }

}
