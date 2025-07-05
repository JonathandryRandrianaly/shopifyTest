<?php
namespace App\Command;
 
 
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use App\Service\MessageHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
 

class WebsocketServerCommand extends Command
{
    protected static $defaultName = "run:websocket-server";

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $port = 9001;
		$host = '127.0.0.1';
		
        $output->writeln("\nStarting server on port " . $port);
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new MessageHandler()
                )
            ),
            $port,
			$host
        );
        $server->run();
        return 0;
        }
}