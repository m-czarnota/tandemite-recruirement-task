<?php

declare(strict_types=1);

namespace App\Client\Infrastructure\ORM;

use App\Client\Domain\Client;
use App\Client\Domain\ClientRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Client>
 *
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctrineClientRepository extends ServiceEntityRepository implements ClientRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    public function add(Client $client): void
    {
        $em = $this->getEntityManager();
        $em->persist($client);

        foreach ($client->getFiles() as $file) {
            $em->persist($file);
        }
    }

    public function findOneById(string $id): ?Client
    {
        return $this->find($id);
    }

    public function findPage(int $pageNumber, int $pageSize): array
    {
        return $this->createQueryBuilder('c')
            ->setMaxResults($pageSize)
            ->setFirstResult(($pageNumber - 1) * $pageSize)
            ->getQuery()
            ->getResult();
    }

    public function findCount(): int
    {
        return $this->count();
    }
}
