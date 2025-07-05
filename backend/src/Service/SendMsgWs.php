<?php

namespace App\Service;

class SendMsgWs
{
  
   public static function send(string $txt){

            $client = @new \WebSocket\Client("ws://10.106.71.84:9001") or ($client = false);

            try{
                    @$client->text($txt) or ($client = false);
            } catch (\Exception $exc) {}
   }
}