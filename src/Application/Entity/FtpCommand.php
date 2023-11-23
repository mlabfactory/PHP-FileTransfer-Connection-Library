<?php
namespace Mlab\Ftpservice\Application\Entity;

use Mlab\Ftpservice\Application\Service\FtpServiceConnection;

class FtpCommand implements CommandInterface {
    
    private \FTP\Connection $connection;

    public function __construct(\FTP\Connection $connection) {
        $this->connection = $connection;
    }

    public function alloc($filesize, &$result = null) {
        return ftp_alloc($this->connection, $filesize, $result);
    }

    public function append($remoteFile, $localFile, $mode = FTP_BINARY) {
        return ftp_append($this->connection, $remoteFile, $localFile, $mode);
    }

    public function cdup() {
        return ftp_cdup($this->connection);
    }

    public function chdir($directory) {
        return ftp_chdir($this->connection, $directory);
    }

    public function chmod($mode, $filename) {
        return ftp_chmod($this->connection, $mode, $filename);
    }

    public function delete($path) {
        return ftp_delete($this->connection, $path);
    }

    public function exec($command) {
        return ftp_exec($this->connection, $command);
    }

    public function fget($remoteFile, $localFile) {
        return ftp_fget($this->connection, $localFile, $remoteFile);
    }

    public function get_option($option) {
        return ftp_get_option($this->connection, $option);
    }

    public function get($remoteFile, $localFile) {
        return ftp_get($this->connection, $localFile, $remoteFile);
    }

    public function mdtm($remoteFile) {
        return ftp_mdtm($this->connection, $remoteFile);
    }

    public function mkdir($directory) {
        return ftp_mkdir($this->connection, $directory);
    }

    public function mlsd($directory) {
        return ftp_mlsd($this->connection, $directory);
    }

    public function nb_continue() {
        return ftp_nb_continue($this->connection);
    }

    public function pasv($pasv) {
        return ftp_pasv($this->connection, $pasv);
    }

    public function put($remoteFile, $localFile, $mode = FTP_BINARY) {
        return ftp_put($this->connection, $remoteFile, $localFile, $mode);
    }

    public function pwd() {
        return ftp_pwd($this->connection);
    }

    public function quit() {
        return ftp_quit($this->connection);
    }

    public function raw($command) {
        return ftp_raw($this->connection, $command);
    }

    public function rawlist($directory, $recursive = false) {
        return ftp_rawlist($this->connection, $directory, $recursive);
    }

    public function rename($oldname, $newname) {
        return ftp_rename($this->connection, $oldname, $newname);
    }

    public function rmdir($directory) {
        return ftp_rmdir($this->connection, $directory);
    }

    public function size($remoteFile) {
        return ftp_size($this->connection, $remoteFile);
    }
}
