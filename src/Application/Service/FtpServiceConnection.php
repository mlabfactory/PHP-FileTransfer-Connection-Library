<?php
namespace Mlab\Ftpservice\Application\Service;

use Mlab\Ftpservice\Application\Entity\CommandInterface;
use Mlab\Ftpservice\Application\Entity\FtpCommand;
use Mlab\Ftpservice\Application\Exception\ConnectionException;
use Mlab\Ftpservice\Interface\ConnectionInterface;

class FtpServiceConnection implements ConnectionInterface {

    private readonly string $user;
    private readonly string $password;
    private readonly string $host;
    private readonly bool $ssl;
    private readonly int $port;
    private readonly int $timeout;

    private \FTP\Connection $connection;

    /**
     * @param string $user of ftp connection
     * @param string password of ftp connection
     * @param string $host of ftp
     * @param array $options <string:mixed> ( ['path', 'ssl', 'file_key', 'port', 'timeout'])
     */
    private function __construct(string $user, string $password, string $host, array $options = [])
    {
        $this->user = $user;
        $this->password = $password;
        $this->host = $host;
        $this->ssl = false;
        $this->port = 21;
        $this->timeout = 90;

        $this->setOptions($options);
        $this->connect();

    }

    /**
     * set up FTP options
     */
    protected function setOptions(array $options)
    {

        $this->port = empty($options['port']) ? 22 : (int) $options['port'];
        $this->port = empty($options['timeout']) ? 90 : (int) $options['timeout'];

        if(!empty($options['ssl'])) {
            if(!is_bool($options['ssl'])) throw new ConnectionException("SSL value in array must be boolean", 401);

            $this->ssl = $options['ssl'];
        }

    }

    /**
     * make FTP connection
     */
    public function connect(): self
    {
        if($this->ssl === true) {
            $connection = @ftp_ssl_connect(
                $this->host, $this->port, $this->timeout
            );
        } else {
            $connection = @ftp_connect(
                $this->host, $this->port, $this->timeout
            );
        }

        if($connection === false) {
            throw new ConnectionException("Unable make connection to FTP", 401);
        }

        $this->connection = $connection;

        return $this;

    }

    /**
     * login to FTP
     */
    public function login(): CommandInterface
    {
        $connection = @ftp_login($this->connection,$this->user,$this->password);

        if($connection === false) {
            throw new ConnectionException("Unable connect to FTP ".$this->host, 401);
        }

        return new FtpCommand($this->connection);
    }

    public function close(): void
    {
        ftp_close($this->connection);
    }


    /**
     * Get the value of connection
     *
     * @return \FTP\Connection
     */
    public function getConnection(): \FTP\Connection
    {
        return $this->connection;
    }
}