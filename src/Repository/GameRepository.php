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


    // On fait un join lorsqu'on veut accéder à des propriétés d'une entité 
    // 2 cas : 
    // - Si on a directement notre entité en propriété, on passe par un join normal => -> join('g.countries', 'c') => propriété inversedBy
    // - Si notre entité à join n'est pas directement en tant que propriété ==> -> join(Library::class, 'lib', Join::WITH, 'lib.game =g')
    // -> select('g', 'c', 'genre', 'comment', 'p')  ==> selectionne toutes les propriétés de notre entité visé 
    // -> leftJoin('g.comments', 'comment')  ==> permet de gérer le cas ou certaines valeurs peuvent être null
    // -> getOneOrNullResult();  ==> permet de récupérer un objet et non un tableau d'objet
    // Dans le twig, on aura accès à toutes les propriétés des entités countries, genre, comment et publisher
    // -> join('comment.account', 'a') => permet lier la table account à la table comment pour récupérer le nom des personnes qui poste des commantaires

    public function getGameDetails($slug)
    {
        return $this -> createQueryBuilder('g')
        -> select('g', 'c', 'genre', 'comment', 'p', 'a')
        -> join('g.countries', 'c')
        -> join('g.genres', 'genre')
        -> leftJoin('g.comments', 'comment')
        -> leftJoin('g.publisher', 'p')
        -> join('comment.account', 'a')
        -> andWhere('g.slug = :game_slug')
        -> setParameter('game_slug', $slug)
        -> orderBy('comment.createdAt', 'DESC')
        -> getQuery() 
        -> getOneOrNullResult();
    }


}
