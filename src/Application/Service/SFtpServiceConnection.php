<?php

namespace Mlab\Ftpservice\Application\Service;

use Mlab\Ftpservice\Interface\ConnectionInterface;
use Mlab\Ftpservice\Application\Entity\SftpCommand;
use Mlab\Ftpservice\Application\Entity\CommandInterface;
use Mlab\Ftpservice\Application\Exception\ConnectionException;
use phpseclib3\Net\SFTP;

class SFtpServiceConnection implements ConnectionInterface
{

    private readonly string $user;
    private readonly string $password;
    private readonly string $host;
    private string $privkeyfile;
    private int $port;
    private int $timeout;

    private SFTP $connection;

    /**
     * @param string $user of ftp connection
     * @param string password of ftp connection
     * @param string $host of ftp
     */
    public function __construct(string $user, string $password, string $host, array $options = [])
    {
        if (!extension_loaded('ssh2')) {
            throw new ConnectionException("The PECL ssh2 extension is not enabled in this PHP environment \n
            check these link for installing instructions https://www.php.net/manual/en/ssh2.installation.php",
            500);
        }

        $this->user = $user;
        $this->password = $password;
        $this->host = $host;
        $this->port = 21;
        $this->timeout = 90;

        $this->setOptions($options);
        $this->connect();
    }

    /**
     * set up FTP options
     * @param array $options <string:mixed> ( ['path', 'method', 'privkeyfile', 'pubkeyfile', 'port', 'timeout'])
     * 
     */
    protected function setOptions(array $options)
    {

        $this->port = empty($options['port']) ? 22 : (int) $options['port'];
        $this->timeout = empty($options['timeout']) ? 90 : (int) $options['timeout'];

        if (!empty($options['privkeyfile'])) {
            $content = file_get_contents($options['privkeyfile']);

            if ($content === false && $this->isPrivateKey($content) === false) {
                throw new ConnectionException("File privkeyfile must be valid", 401);
            }

            $this->privkeyfile = $content;
        }

    }

    /**
     * make FTP connection
     */
    public function connect(): self
    {
        $connection = new SFTP($this->host, $this->port, $this->timeout);
        $this->connection = $connection;

        return $this;
    }

    /**
     * login to FTP
     */
    public function login(): CommandInterface
    {
        $connection = false;

        if (empty($this->privkeyfile)) {
            $connection = $this->connection->login($this->user, $this->password);
        }

        if (!empty($this->privkeyfile)) {
            $connection = $this->connection->login($this->user, $this->privkeyfile);
        }

        if ($connection === false) {
            throw new ConnectionException("Unable connect to FTP " . $this->host, 401);
        }

        return new SftpCommand($this->connection);
    }

    public function close(): void
    {
        
    }


    /**
     * Get the value of connection
     *
     * @return mixed
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * chek if is a valid private key
     * @param string $keyString
     * 
     * @return bool
     */
    private function isPrivateKey($keyString): bool
    {
        return strpos(trim($keyString), '-----BEGIN OPENSSH PRIVATE KEY-----') === 0;
    }
}
