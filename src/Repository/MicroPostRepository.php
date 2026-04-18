<?php

namespace App\Repository;

use App\Entity\MicroPost;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MicroPost>
 */
class MicroPostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MicroPost::class);
    }

    public function findOneWithCommentsById(int $postId): MicroPost|null
    {
        return $this->createQueryBuilder('p')
            ->addSelect('c', 'l')
            ->leftJoin('p.comments', 'c')
            ->leftJoin('p.likedBy', 'l')
            ->andWhere('p.id = :postId')
            ->setParameter('postId', $postId)
            ->orderBy('c.created', 'DESC')
            ->getQuery()
            ->getOneOrNullResult();
    }

    /** @return MicroPost[] */
    public function findAllWithComments(): array
    {
        return $this->findAllQueries(
            withComments: true,
            withLikes: true,
            withAuthors: true,
            withProfiles: true,
        )->getQuery()->getResult();
    }

    /** @return list<int>*/
    public function findAllWithMinLikes(int $minLikes): array
    {
        $idList = $this->findAllQueries(withLikes: true)
            ->select('p.id')
            ->groupBy('p.id')
            ->andHaving('COUNT(l) >= :minLikes')
            ->setParameter('minLikes', $minLikes)
            ->getQuery()
            ->getResult(Query::HYDRATE_SCALAR_COLUMN);

        return $this->findAllQueries(
            withComments: true,
            withLikes: true,
            withAuthors: true,
            withProfiles: true,
        )
            ->andWhere('p.id IN (:idList)')
            ->setParameter('idList', $idList)
            ->getQuery()
            ->getResult();
    }

    /** @return MicroPost[] */
    public function findAllByAuthor(int|User $author): array
    {
        return $this->findAllQueries(
            withComments: true,
            withLikes: true,
            withAuthors: true,
            withProfiles: true,
        )->andWhere('p.author = :author')
            ->setParameter(
                'author',
                $author instanceof User ? $author->getId() : $author,
            )
            ->getQuery()
            ->getResult();
    }

    /** @return MicroPost[] */
    public function findAllByAuthors(Collection|array $authors): array
    {
        return $this->findAllQueries(
            withComments: true,
            withLikes: true,
            withAuthors: true,
            withProfiles: true,
        )
            ->andWhere('p.author IN (:authors)')
            ->setParameter('authors',$authors)
            ->getQuery()
            ->getResult();
    }

    private function findAllQueries(
        bool $withComments = false,
        bool $withLikes = false,
        bool $withAuthors = false,
        bool $withProfiles = false,
    ): QueryBuilder
    {
        $query = $this->createQueryBuilder('p');

        if ($withComments) {
            $query->leftJoin('p.comments', 'c')
                ->addSelect('c');
        }

        if ($withLikes) {
            $query->leftJoin('p.likedBy', 'l')
                ->addSelect('l');
        }

        if ($withAuthors || $withProfiles) {
            $query->leftJoin('p.author', 'a')
                ->addSelect('a');
        }

        if ($withProfiles) {
            $query->leftJoin('a.userProfile', 'up')
                ->addSelect('up');
        }

        return $query->orderBy('p.created', 'DESC');
    }
}
