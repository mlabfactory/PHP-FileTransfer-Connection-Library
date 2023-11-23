<?php
namespace Mlab\Ftpservice\Application;

use Mlab\Ftpservice\Enum\Protocol;
use Mlab\Ftpservice\Application\Service\TransferService;

class TransferConnector {

    /**
     * instance of new TransferConnector
     * @param string $user
     * @param string $password
     * @param string $host
     * @param Protocol $type
     * 
     * @return TransferService
     */
    public function __construct(string $user, string $password, string $host, Protocol $type = Protocol::FTP)
    {
        return TransferService::init($user, $password, $host, $type);
    }
}