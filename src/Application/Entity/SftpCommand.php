<?php
namespace Mlab\Ftpservice\Application\Entity;

use Mlab\Ftpservice\Application\Exception\FileTransferException;
use phpseclib3\Net\SFTP;

class SftpCommand implements CommandInterface {

    private SFTP $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function alloc($filesize, &$result = null) {
        throw new FileTransferException("Comman alloc is unable in SSH");
    }

    public function append($remoteFile, $localFile, $mode = SFTP::RESUME)
    {
        return $this->connection->put('filename.remote', $localFile, $mode);
    }

    public function cdup() {
        return $this->connection->chdir('...');
    }

    public function chdir($directory) {
        return $this->connection->chdir($directory);
    }

    public function chmod($mode, $filename) {
        return $this->connection->chmod($mode, $filename);
    }

    public function delete($path) {
        return $this->connection->delete($path);
    }

    public function fget($remoteFile, $localFile) {
        return $this->connection->get($remoteFile, $localFile);
    }

    public function put($localFile, $remoteFile) {
        return $this->connection->put($remoteFile, $localFile, SFTP::SOURCE_LOCAL_FILE);
    }

    public function get($remoteFile, $localFile) {
        return $this->connection->get($remoteFile, $localFile);
    }

    public function mdtm($remoteFile) {
        return $this->connection->filemtime($remoteFile);
    }

    public function mkdir($directory) {
        return $this->connection->mkdir($directory);
    }

    public function mlsd($directory) {
        return $this->connection->rawlist($directory);
    }

    public function pwd() {
        return $this->connection->pwd();
    }

    public function rename($oldname, $newname) {
        return $this->connection->rename($oldname, $newname);
    }

    public function rmdir($directory) {
        return $this->connection->rmdir($directory);
    }

    public function size($remoteFile) {
        return $this->connection->filesize($remoteFile);
    }

    public function rawlist($directory, $recursive = false)
    {
        return $this->connection->rawlist($directory, $recursive);
    }

}
