<?php

namespace App\Service;


class CMysql {

    public $user;
    public $password;
    public $host;
    public $port; 
    public $db;
    private $mysqli;
    private $path;
    private $csvpath;
    private $sit;

    public function __construct(string $host, string $path, string $csvpath, string $sit){

        //root:@127.0.0.1:3306/ddc_etats?serverVersion=8&charset=utf8mb4"

        $this->host = explode('@',explode(':',explode('/',explode('?',$host)[0])[2])[1])[1]; 
        $this->port = explode(':',explode('/',explode('?',$host)[0])[2])[2]; 
        $this->user = explode(':',explode('/',explode('?',$host)[0])[2])[0];
        $this->password = explode('@',explode(':',explode('/',explode('?',$host)[0])[2])[1])[0];
        $this->db = explode('/',explode('?',$host)[0])[3];
        $this->path = $path;
        $this->csvpath = $csvpath;
        $this->sit = $sit;

    }

    public function openMysqli(){
        $this->mysqli = new \mysqli($this->host, $this->user, $this->password, $this->db);
        return $this->mysqli;
    }

    public function closeMysqli(){
        $this->mysqli->close();
        $this->mysqli = null;
    }

}