<?php
namespace Mlab\Ftpservice\Application\Entity;

use Mlab\Ftpservice\Application\Exception\FileTransferException;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class S3AwsCommand implements CommandInterface {

    private S3Client $s3Client;

    public function __construct($s3Client) {
        $this->s3Client = $s3Client;
    }

    public function alloc($filesize, &$result = null) {
        // L'allocazione dello spazio non è applicabile a S3, quindi restituiamo un risultato di successo fittizio
        $result = 'Allocazione di spazio non supportata su Amazon S3';
        return true;
    }

    public function append($remoteFile, $localFile, $mode = FTP_BINARY) {
        // Carica il file locale su S3
        try {
            $this->s3Client->putObject([
                'Bucket' => 'nome-del-tuo-bucket',
                'Key' => $remoteFile,
                'SourceFile' => $localFile,
            ]);
        } catch (AwsException $e) {
            // Gestisci eventuali errori durante il caricamento del file
            throw new FileTransferException("Errore durante l'append del file su Amazon S3: " . $e->getMessage());
        }
    }

    public function cdup() {
        // CDUP non è applicabile a S3, poiché non ci sono directory concettuali come su un server FTP tradizionale
        throw new FileTransferException('CDUP non supportato su Amazon S3');
    }

    public function chdir($directory) {
        // CHDIR non è applicabile a S3, poiché non ci sono directory concettuali come su un server FTP tradizionale
        throw new FileTransferException('CHDIR non supportato su Amazon S3');
    }

    public function chmod($mode, $filename) {
        // CHMOD non è applicabile a S3, poiché S3 non gestisce permessi di file come un filesystem tradizionale
        throw new FileTransferException('CHMOD non supportato su Amazon S3');
    }

    public function delete($path) {
        // Elimina un oggetto (file) da S3
        try {
            $this->s3Client->deleteObject([
                'Bucket' => 'nome-del-tuo-bucket',
                'Key' => $path,
            ]);
        } catch (AwsException $e) {
            // Gestisci eventuali errori durante l'eliminazione dell'oggetto
            throw new FileTransferException("Errore durante l'eliminazione del file su Amazon S3: " . $e->getMessage());
        }
    }

    public function fget($remoteFile, $localFile) {
        try {
            $this->s3Client->getObject([
                'Bucket' => 'nome-del-tuo-bucket',
                'Key' => $remoteFile,
                'SaveAs' => $localFile,
            ]);
        } catch (AwsException $e) {
            throw new FileTransferException("Errore durante la lettura del file da Amazon S3: " . $e->getMessage());
        }
    }

    public function get($remoteFile, $localFile) {
        // Puoi utilizzare fget anche per il download
        $this->fget($remoteFile, $localFile);
    }

    public function mdtm($remoteFile) {
        try {
            $result = $this->s3Client->headObject([
                'Bucket' => 'nome-del-tuo-bucket',
                'Key' => $remoteFile,
            ]);

            return strtotime($result['LastModified']);
        } catch (AwsException $e) {
            throw new FileTransferException("Errore durante il recupero della data di modifica da Amazon S3: " . $e->getMessage());
        }
    }

    public function mkdir($directory) {
        try {
            $this->s3Client->putObject([
                'Bucket' => 'nome-del-tuo-bucket',
                'Key' => rtrim($directory, '/') . '/', // Assicurati che il percorso termini con una barra
                'Body' => '',
            ]);
        } catch (AwsException $e) {
            throw new FileTransferException("Errore durante la creazione della directory su Amazon S3: " . $e->getMessage());
        }
    }

    public function mlsd($directory) {
        try {
            $objects = $this->s3Client->listObjects([
                'Bucket' => 'nome-del-tuo-bucket',
                'Prefix' => $directory,
            ]);

            $result = [];
            foreach ($objects['Contents'] as $object) {
                $result[] = [
                    'name' => basename($object['Key']),
                    'size' => $object['Size'],
                    'type' => 'file', // Puoi migliorare la logica qui per determinare se è una directory
                ];
            }

            return $result;
        } catch (AwsException $e) {
            throw new FileTransferException("Errore durante il recupero dell'elenco da Amazon S3: " . $e->getMessage());
        }
    }

    public function put($remoteFile, $localFile) {
        $this->append($remoteFile, $localFile);
    }

    public function pwd() {
        // Restituisci il nome del bucket come percorso corrente
        return 'nome-del-tuo-bucket';
    }

    public function rawlist($directory, $recursive = false) {
        // L'implementazione di RAWLIST può variare in base alla tua logica specifica
        // Questo è solo un esempio di base
        return $this->mlsd($directory);
    }

    public function rename($oldname, $newname) {
        try {
            $this->s3Client->copyObject([
                'Bucket' => 'nome-del-tuo-bucket',
                'CopySource' => 'nome-del-tuo-bucket/' . $oldname,
                'Key' => $newname,
            ]);

            $this->s3Client->deleteObject([
                'Bucket' => 'nome-del-tuo-bucket',
                'Key' => $oldname,
            ]);
        } catch (AwsException $e) {
            throw new FileTransferException("Errore durante il rinomina su Amazon S3: " . $e->getMessage());
        }
    }

    public function rmdir($directory) {
        try {
            // L'eliminazione di un bucket su S3 elimina tutti gli oggetti al suo interno
            $this->s3Client->deleteBucket([
                'Bucket' => 'nome-del-tuo-bucket',
            ]);
        } catch (AwsException $e) {
            throw new FileTransferException("Errore durante l'eliminazione della directory su Amazon S3: " . $e->getMessage());
        }
    }

    public function size($remoteFile) {
        try {
            $result = $this->s3Client->headObject([
                'Bucket' => 'nome-del-tuo-bucket',
                'Key' => $remoteFile,
            ]);

            return $result['ContentLength'];
        } catch (AwsException $e) {
            throw new FileTransferException("Errore durante il recupero della dimensione da Amazon S3: " . $e->getMessage());
        }
    }
}


