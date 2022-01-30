<?php

ini_set('display_errors', 1);
ini_set('display_startup-errors', 1);
error_reporting(E_ALL);

// Encuentra todas las librerias que tengamos de manera automatica
require 'vendor/autoload.php';

use Aws\S3\MultipartUploader;
use Aws\S3\S3Client;
use Aws\Exception\S3Exception;
use Aws\Exception\MultipartUploadException;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$nombre = $_FILES['archivo']['name'];
$guardado = $_FILES['archivo']['tmp_name'];

if(!file_exists('archivos')) {
    // mkdir('archivos', 0777, true);
    if(file_exists('archivos')) {
        if(move_uploaded_file($guardado, '' . $nombre)) {
            uploadFileToBucket($nombre, $nombre);
        } else {
            echo 'Error primer if';
        }
    }
} else {
    if(move_uploaded_file($guardado, '' . $nombre)) {
        uploadFileToBucket($nombre, $nombre);
    } else {
        echo 'Error primer if';
    }
}

// REDIRECCIÓN -----------------------------------------------------------------
header('Location: https://informatica.ieszaidinvergeles.org:10050/PIA/RekognitionPrueba/process.php?file=' . $nombre . '&name=' . $nombre);
exit;


// FUNCIONES -------------------------------------------------------------------
function uploadFileToBucket($file, $key) {
    $result = false;
    try {
        $s3 = new S3Client([
            'version'     => 'latest',
            'region'      => 'us-east-1', //depends on the value of your region
            'credentials' => [
                'key'    => $_ENV['aws_access_key_id'],
                'secret' => $_ENV['aws_secret_access_key'],
                'token'  => $_ENV['aws_session_token']
            ]
        ]);
        $uploader = new MultipartUploader($s3, $file, [
            'bucket' => $_ENV['bucket_name'],
            'key'    => $key,
        ]);
        $result = $uploader->upload();
    } catch(MultipartUploadException $e) {
        $e->getMessage();
    } catch (S3Exception $e) {
        $e->getMessage();
    }
    return $result;
}