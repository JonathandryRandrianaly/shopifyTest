<?php

namespace App\Repository;

use App\Entity\Usr;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Usr|null find($id, $lockMode = null, $lockVersion = null)
 * @method Usr|null findOneBy(array $criteria, array $orderBy = null)
 * @method Usr[]    findAll()
 * @method Usr[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsrRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Usr::class);
    }

    public function findByUsername($code)
    {
        return $this->findOneBy(['username' => $code]);
    }

    public function findByRoles(array $roleNames): array
    {
        $sql = "
            SELECT u.username
            FROM usr u
            WHERE (
                " . implode(' OR ', array_map(fn($key) => "JSON_CONTAINS(u.roles, :role$key, '$')", array_keys($roleNames))) . "
            )
        ";
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        foreach ($roleNames as $key => $roleName) {
            $stmt->bindValue(":role$key", json_encode($roleName));
        }
        return $stmt->executeQuery()->fetchAllAssociative();
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Usr) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    // /**
    //  * @return Usr[] Returns an array of Usr objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Usr
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
