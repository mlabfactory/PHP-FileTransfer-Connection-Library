<?php
namespace Mlab\Ftpservice\Application\Service;

use Mlab\Ftpservice\Application\Entity\CommandInterface;
use Mlab\Ftpservice\Application\Entity\FtpCommand;
use Mlab\Ftpservice\Enum\Protocol;
use Mlab\Ftpservice\Interface\ConnectionInterface;

class TransferService {

    private CommandInterface $command;

    private function __construct(ConnectionInterface $connection)
    {
        $command = $connection->login();
        $this->command = $command;
    }

    /**
     * instance of new TransferConnector
     * @param string $user
     * @param string $password
     * @param string $host
     * @param Protocol $type
     * 
     * @return ConnectionInterface
     */
    public static function init($user, $password, $host, Protocol $type)
    {
        switch($type) {
            case Protocol::FTP:
                return new self(new FtpServiceConnection(
                    $user, $password, $host
                ));
                break;
            case Protocol::SSH:
                return new self(new SFtpServiceConnection(
                    $user, $password, $host
                ));
                break;
        }
    }

    /**
     * Get the value of command
     *
     * @return CommandInterface
     */
    public function getCommand(): CommandInterface
    {
        return $this->command;
    }
}