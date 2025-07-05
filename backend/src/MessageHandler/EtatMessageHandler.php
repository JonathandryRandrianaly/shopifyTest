<?php

namespace App\MessageHandler;

use App\Message\EtatMessage;
use App\Services\ExcelService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class EtatMessageHandler implements MessageHandlerInterface
{
    // private $excelService;

    // public function __construct(ExcelService $excelService)
    // {
    //     $this->excelService = $excelService;
    // }

    public function __invoke(EtatMessage $message)
    {
        // $this->excelService->etat_ouverture_credoc($message->getDateDebut(), $message->getDateFin());
    }
}
