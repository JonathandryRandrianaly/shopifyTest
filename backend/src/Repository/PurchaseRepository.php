<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\Purchase;
use App\Entity\Stock;
use App\Entity\Supplier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\SupplierRepository;
use App\Repository\ProductRepository;

/**
 * @extends ServiceEntityRepository<Purchase>
 *
 * @method Purchase|null find($id, $lockMode = null, $lockVersion = null)
 * @method Purchase|null findOneBy(array $criteria, array $orderBy = null)
 * @method Purchase[]    findAll()
 * @method Purchase[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PurchaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Purchase::class);
    }
    function excelDateToDateTime(float $excelDate): \DateTime
    {
        $timestamp = ($excelDate - 25569) * 86400;
        return (new \DateTime())->setTimestamp((int)$timestamp);
    }
    public function importPurchase(array $datas,SupplierRepository $supplierRep, ProductRepository $productRep,StockRepository $stockRep,$endPoint)
    {
        foreach ($datas as $row) {
            $supplier = $supplierRep->findOneBy(['name' => $row[1]]);
            if (!$supplier) {
                $supplier = new Supplier();
                $supplier->setName($row[1]);
                $supplierRep->add($supplier, true);
            }
            $product = $productRep->findOneBy(['reference' => $row[2]]);
            if (!$product) {
                $product = new Product();
                $product->setSupplier($supplier);
                $product->setName($row[3]);
                $product->setReference($row[2]);
                $productRep->add($product, true);
            }
            $date = $this->excelDateToDateTime((float)$row[0]);
            $purchase = new Purchase();
            $purchase->setDate($date);
            $purchase->setProduct($product);
            $purchase->setQuantity($row[4]);
            $purchase->setPurchasePrice((string)$row[5]);
            $this->add($purchase);
        }
        //Update shopifyPrice before adding stock
        $productRep->callShopifyApi($endPoint);

        foreach ($datas as $row) {
            $product = $productRep->findOneBy(['reference' => $row[2]]);
            $date = $this->excelDateToDateTime((float)$row[0]);
            $stock = new Stock();
            $stock->setDate($date);
            $stock->setProduct($product);
            $stock->setQuantityIn($row[4]);
            $stock->setPurchasePrice((string)$row[5]);
            $stock->setShopifyPrice((string) $product->getShopifyPrice());

            $stockRep->add($stock);
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Purchase $entity, bool $flush = true): void
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
    public function remove(Purchase $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Purchase[] Returns an array of Purchase objects
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
    public function findOneBySomeField($value): ?Purchase
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
