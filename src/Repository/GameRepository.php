<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\Library;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function add(Game $game, bool $flush = true): void {
        $this->_em->persist($game);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(Game $game, bool $flush = true): void {
        $this->_em->remove($game);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findLastGames(): array {
        return $this->createQueryBuilder('g')
            ->orderBy('g.publishedAt', 'DESC')
            ->setMaxResults(9)
            ->getQuery()
            ->getResult()
        ;
    }

    // use Doctrine\ORM\Query\Expr\Join; <- c'est celui-ci qu'il faut importer
    public function getMostPlayedGames($limit = 9)
    {
        return $this -> createQueryBuilder('g')
        // Lors d'une relation unilatéral (ici, library non présente dans game mais game présent dans library)
        -> join(Library::class, 'lib', Join::WITH, 'lib.game = g')
        -> groupBy('g.name')
        -> orderBy('SUM(lib.gameTime)', 'DESC')
        -> setMaxResults($limit)
        -> getQuery() -> getResult();
    }

    // Les 9 jeux les plus achetés (3 par lignes), sous le label : "Les plus vendus" /!\
    public function getMostBoughtGames($limit = 9)
    {
        return $this -> createQueryBuilder('g')
        -> join(Library::class, 'lib', Join::WITH, 'lib.game =g')
        -> groupBy('g.name')
        -> orderBy('COUNT(g.id)','DESC')
        -> setMaxResults($limit)
        -> getQuery() -> getResult();
    }

    // Fonction optimisée des 2 fonctions d'au-dessus
    /**
     * @param string $orderBy une chaîne de caractère sur laquelle trier nos jeux
     * @param string $descAsc L'ordre du trie (par défaut DESC)
     * @param int|null $limit le nombre de jeux à récupérer (par défaut 9)
     */
    public function getMostGameByOrderBy(string $orderBy, string $descAsc = 'DESC', ?int $limit = 9)
    {
        return $this -> createQueryBuilder('g')
        -> join(Library::class, 'lib', Join::WITH, 'lib.game =g')
        -> groupBy('g.name')
        -> orderBy($orderBy, $descAsc)
        -> setMaxResults($limit)
        -> getQuery() -> getResult();
    }
}
