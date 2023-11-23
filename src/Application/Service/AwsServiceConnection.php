<?php

namespace Mlab\Ftpservice\Application\Service;

use Aws\Exception\AwsException;
use Mlab\Ftpservice\Interface\ConnectionInterface;
use Mlab\Ftpservice\Application\Entity\SftpCommand;
use Mlab\Ftpservice\Application\Entity\CommandInterface;
use Mlab\Ftpservice\Application\Exception\ConnectionException;
use Aws\S3\S3Client;
use Exception;
use Mlab\Ftpservice\Application\Entity\S3AwsCommand;

class AwsServiceConnection implements ConnectionInterface
{

    private readonly string $key;
    private readonly string $secret;
    private readonly string $region;
    private string $version;
    private int $timeout;

    private S3Client $S3Client;

    /**
     * @param string $key of ftp connection
     * @param string secret of ftp connection
     * @param string $region of ftp
     */
    public function __construct(string $key, string $secret, string $region, array $options = [])
    {

        $this->key = $key;
        $this->secret = $secret;
        $this->region = $region;
        $this->timeout = 90;
        $this->version = "latest";

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

        $this->timeout = empty($options['timeout']) ? 90 : (int) $options['timeout'];
        $this->version = empty($options['version']) ? 90 : (int) $options['version'];
    }

    /**
     * make FTP connection
     */
    public function connect(): self
    {
        try {
            $connection = new S3Client([
                'region' => $this->region,
                'version' => $this->version,
                'credentials' => [
                    'key' => $this->key,
                    'secret' => $this->secret,
                ],
            ]);
            
        } catch(AwsException $e) {
            throw new ConnectionException($e->getMessage(), 500);
        }
        
        $this->S3Client = $connection;

        return $this;
    }

    /**
     * login to FTP
     */
    public function login(): CommandInterface
    {
        return new S3AwsCommand($this->S3Client);
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
        return $this->S3Client;
    }

}
