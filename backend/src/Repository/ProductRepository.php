<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    private HttpClientInterface $httpClient;
    public function __construct(ManagerRegistry $registry, HttpClientInterface $httpClient)
    {
        parent::__construct($registry, Product::class);
        $this->httpClient = $httpClient;
    }

    public function callShopifyApi($endpoint): void
    {
        $response = $this->httpClient->request('GET', $endpoint);

        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException('Failed to fetch Shopify prices');
        }

        $data = $response->toArray();

        foreach ($data as $item) {
            $ref = $item['refProduit'] ?? null;
            $price = $item['prixVente'] ?? null;

            if (!$ref || !$price) {
                continue;
            }

            $product = $this->findOneBy(['reference' => $ref]);
            if ($product) {
                $product->setShopifyPrice($price);
                $this->_em->persist($product);
            }
        }
        $this->_em->flush();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Product $entity, bool $flush = true): void
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
    public function remove(Product $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
