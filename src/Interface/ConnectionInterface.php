<?php
namespace Mlab\Ftpservice\Interface;

use Mlab\Ftpservice\Application\Entity\CommandInterface;

interface ConnectionInterface {

    /**
     * make FTP connection
     */
    public function connect(): self;

    /**
     * login to FTP
     */
    public function login(): CommandInterface;

    public function close();

    /**
     * Get the value of connection
     *
     * @return \FTP\Connection
     */
    public function getConnection();
}