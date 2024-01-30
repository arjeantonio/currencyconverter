<?php

namespace App\Repository;

use App\Entity\WhitelistEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WhitelistEntry>
 *
 * @method WhitelistEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method WhitelistEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method WhitelistEntry[]    findAll()
 * @method WhitelistEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WhitelistEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WhitelistEntry::class);
    }

    /**
     * isRequestAllowed
     *
     * @param  string $ip_address
     * @return bool
     */
    public function isRequestAllowed($ip_address): bool
    {
        $existingWhitelistEntry = $this->findOneBy(['ip_address' => $ip_address]);
        if ($existingWhitelistEntry instanceof WhitelistEntry) {
            return true;
        }

        return false;
    }

    //    /**
    //     * @return WhitelistEntry[] Returns an array of WhitelistEntry objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('w.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?WhitelistEntry
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
