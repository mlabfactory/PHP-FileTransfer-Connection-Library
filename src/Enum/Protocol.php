<?php
namespace Mlab\Ftpservice\Enum;

enum Protocol:string {
    case FTP = 'Ftp';
    case SSH = 'Sftp';
    case S3 = 'S3';
}