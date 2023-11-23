# PHP File Transfer Connection Library

The PHP FTP/SFTP Connection Library is a versatile and user-friendly tool designed to facilitate seamless connections to FTP and SFTP servers. This library empowers developers to perform a wide range of file operations, including uploading, downloading, and reading files, as well as directory navigation.

### Supported methods:
- FTP
- SFTP
- S3 AWS

### Key Features:

- Flexible Connectivity: Easily establish connections to both FTP and SFTP servers using a straightforward and consistent interface
- Authentication Options: Support for various authentication methods, including password-based authentication, public/private key pairs, and agent-based authentication.
- SFTP Operations: Leverage the library's SFTP capabilities to securely transfer files over SSH, ensuring data integrity and confidentiality.
- Upload and Download: Effortlessly upload files to remote servers or download files to the local system with intuitive methods provided by the library.
- Directory Management: Navigate and manipulate remote directories, create new directories, or remove existing ones using simple yet powerful functions.
- Error Handling: Robust error handling ensures that developers can identify and troubleshoot issues effectively, improving the reliability of file operations.
- Easy to Integrate: Designed with simplicity in mind, the library seamlessly integrates into existing PHP projects, allowing developers to incorporate FTP and SFTP functionality effortlessly.

### Basic Usage

$ftp = new FtpSftpManager("user", "psw","localhost");
$command = $ftp->getCommand();