<?php

namespace App\Repository;

use App\Entity\Stock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @extends ServiceEntityRepository<Stock>
 *
 * @method Stock|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stock|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stock[]    findAll()
 * @method Stock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stock::class);
    }
    public function getRapportData()
    {
        $qb = $this->createQueryBuilder('stock')
            ->select('product.reference AS reference')
            ->addSelect('supplierTable.name AS supplier')
            ->addSelect('SUM(COALESCE(stock.quantityIn,0) - COALESCE(stock.quantityOut,0)) AS quantity')
            ->addSelect('stock.purchasePrice')
            ->addSelect('stock.shopifyPrice')
            ->addSelect('ABS(stock.shopifyPrice - stock.purchasePrice) AS margin')
            ->join('stock.product', 'product')
            ->join('product.supplier', 'supplierTable')
            ->groupBy('product.id');

        return $qb->getQuery()->getResult();
    }
    public function exportRapport(){
        $rapportData = $this->getRapportData();

        $data = [];
        foreach ($rapportData as $record) {
            $data[] = [
                $record['reference'],
                $record['supplier'],
                $record['quantity'],
                $record['purchasePrice'],
                $record['shopifyPrice'],
                $record['margin']
            ];
        }

        $headers = [
            'Ref produit', 'Fournisseur', 'Quantité', 'Prix achat', 'Prix Shopify', 'Marge'
        ];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray(array_merge([$headers], $data), null, 'A1');

        $headerStyleArray = [
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'D3D3D3',
                ],
            ]
        ];
        $sheet->getStyle('A1:F1')->applyFromArray($headerStyleArray);
        $sheet->setAutoFilter('A1:F1');
        $autoFilter = $sheet->getAutoFilter();

        $dataRange = 'A1:F' . (count($data) + 1);
        $sheet->getStyle($dataRange)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);

        $columnDimensions = ['A' => 10, 'B' => 15, 'C' => 30, 'D' => 10, 'E' => 20, 'F' => 15, 'G' => 15, 'H' => 40];
        foreach ($columnDimensions as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }

        $sheet->getStyle('F2:F' . (count($data) + 1))->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
        ]);

        $response = new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });

        return $response;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Stock $entity, bool $flush = true): void
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
    public function remove(Stock $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Stock[] Returns an array of Stock objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Stock
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
