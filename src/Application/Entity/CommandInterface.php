<?php
namespace Mlab\Ftpservice\Application\Entity;

/**
  * Interface for managing FTP commands.
  */
interface CommandInterface {

     /**
      * Allocates a specified space on an FTP server.
      *
      * @param int $filesize The size of space to allocate.
      * @param mixed $result (Output) Result of the allocation.
      * @return mixed The result of the allocation.
      */
     public function alloc($filesize, &$result = null);

     /**
      * Adds data to a remote file on an FTP server.
      *
      * @param string $remoteFile The path to the remote file.
      * @param string $localFile The path to the local file.
      * @param int $mode (Optional) Transfer mode (FTP_ASCII or FTP_BINARY).
      */
     public function append($remoteFile, $localFile, $mode = FTP_BINARY);

     /**
      * Change the current directory of the FTP server to the parent directory.
      */
     public function cdup();

     /**
      * Change the current directory of the FTP server.
      *
      * @param string $directory The path to the new directory.
      */
     public function chdir($directory);

     /**
      * Set the permissions of a file or directory on an FTP server.
      *
      * @param int $mode The new permissions of the file or directory.
      * @param string $filename The name of the file or directory.
      */
     public function chmod($mode, $filename);

     /**
      * Delete a file or directory from the FTP server.
      *
      * @param string $path The path to the file or directory to delete.
      */
     public function delete($path);

     /**
      * Reads a remote file and writes the contents to a local file.
      *
      * @param string $remoteFile The path to the remote file.
      * @param string $localFile The path to the local file.
      */
     public function fget($remoteFile, $localFile);

     /**
      * Download a remote file and save it to a specified local location.
      *
      * @param string $remoteFile The path to the remote file.
      * @param string $localFile The path to the local file.
      */
     public function get($remoteFile, $localFile);

     /**
      * Returns the timestamp of the last modification of a remote file.
      *
      * @param string $remoteFile The path to the remote file.
      * @return int The timestamp of the last modification.
      */
     public function mdtm($remoteFile);

     /**
      * Create a new directory on an FTP server.
      *
      * @param string $directory The path to the new directory.
      */
     public function mkdir($directory);

     /**
      * Returns a detailed list of files in a remote directory.
      *
      * @param string $directory The path to the remote directory.
      * @return array An array with information about the files in the remote directory.
      */
     public function mlsd($directory);

     /**
      * Upload a local file to an FTP server.
      *
      * @param string $remoteFile The path to the remote file.
      * @param string $localFile The path to the local file.
      */
     public function put($remoteFile, $localFile);

     /**
      * Returns the current path on the FTP server.
      *
      * @return string The current path.
      */
     public function pwd();

     /**
      * Returns a detailed list of files in a remote directory (rawlist).
      *
      * @param string $directory The path to the remote directory.
      * @param bool $recursive (Optional) Specifies whether to perform a recursive scan.
      * @return array An array with detailed information about the files in the remote directory.
      */
     public function rawlist($directory, $recursive = false);

     /**
      * Rename a file or directory on the FTP server.
      *
      * @param string $oldname The current name of the file or directory.
      * @param string $newname The new name of the file or directory.
      */
     public function rename($oldname, $newname);

     /**
      * Delete an empty directory from the FTP server.
      *
      * @param string $directory The path to the directory to delete.
      */
     public function rmdir($directory);

     /**
      * Returns the size of a remote file.
      *
      * @param string $remoteFile The path to the remote file.
      * @return int The size of the file.
      */
     public function size($remoteFile);
}