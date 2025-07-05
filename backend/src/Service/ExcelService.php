<?php
// src/Service/ExcelService.php

namespace App\Service;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ExcelService
{
    private $filesystem;
    private $publicDir;
    private $webSocketServer;

    public function __construct(ParameterBagInterface $params, MessageHandler $webSocketServer)
    {
        $this->filesystem = new Filesystem();
        $this->publicDir = $params->get('kernel.project_dir') . '/public';
        $this->webSocketServer = $webSocketServer;
    }

    public function generateExcelFileAsync($dateDebut, $dateFin)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Hello World !');
        $fileName = 'test_generation.xlsx';
        $filePath = $this->publicDir . '/' . $fileName;

        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);
        $fileUrl = '/download/' . $fileName;
        $this->webSocketServer->notifyFileReady($fileUrl);
    }
    public function readExcelFile(string $filePath): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = [];        
        
        foreach ($worksheet->getRowIterator() as $index => $row) {
            if ($index === 1) {
                continue;
            }
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false); 
            $rowData = [];
            foreach ($cellIterator as $cell) {
                $rowData[] = $cell->getValue();
            }
            if (array_filter($rowData)) {
                $rows[] = $rowData;
            }
        }
        
        return $rows;
    }
}


?>
