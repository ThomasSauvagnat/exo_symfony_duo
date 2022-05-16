<?php

namespace App\Repository;

use App\Entity\Forum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Forum>
 *
 * @method Forum|null find($id, $lockMode = null, $lockVersion = null)
 * @method Forum|null findOneBy(array $criteria, array $orderBy = null)
 * @method Forum[]    findAll()
 * @method Forum[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Forum::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Forum $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Forum $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }


    // Fonction pour afficher les pages
    public function getQbAll(){
        return $this->createQueryBuilder('f')
            ->orderBy('f.createdAt', 'DESC');
    }


    // Pour gérer la barre de recherche des forums 
    // Ne pas créer de createQueryBuilder car on va maj la selection du dessus
    public function updateQbByData($qb, $datas){
        dump($datas);

        // Recherche sur le title = le nom de notre champ dans le fichier searchForumType
        if ($datas['title'] !== null) {

            // Recherche selon le title de notre objet forum et lui passe en valeur le champ $datas['title']
            $qb->andWhere('f.title LIKE :param_title')
                ->setParameter('param_title', '%'.$datas['title'].'%');
        }

        // Recherche sur la dateMin
        if ($datas['dateMini'] !== null) {

            // Recherche selon la dateMini de notre objet forum et lui passe en valeur le champ $datas['dateMini']
            $qb->andWhere('f.createdAt > :dateMini')
                ->setParameter('dateMini', $datas['dateMini']);
                dump('Je suis dans dateMini');
        }

        // Recherche sur la dateMax
        if ($datas['dateMax'] !== null) {

            // Recherche selon la dateMax de notre objet forum et lui passe en valeur le champ $datas['dateMax']
            $qb->andWhere('f.createdAt < :dateMax')
                ->setParameter('dateMax', $datas['dateMax']);
        }

        return $qb;
    }


    // /**
    //  * @return Forum[] Returns an array of Forum objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Forum
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
