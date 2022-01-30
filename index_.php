<?php

// Mostrar errores -------------------------------------------------------------
ini_set('display_errors', 1);
ini_set('display_startup-errors', 1);
error_reporting(E_ALL);

// Encuentra todas las librerias que tengamos de manera automatica
require 'vendor/autoload.php';

// Importación de las librerías ------------------------------------------------
use Dotenv\Dotenv;
use Aws\S3\MultipartUploader;
use Aws\S3\S3Client;

// Para utilizar el archivo '.env'
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// VARIABLES -------------------------------------------------------------------
$file = 'personas.jpg';
$name = 'personas.jpg';
uploadFileToBucket($file, $name);

// REDIRECCIÓN -----------------------------------------------------------------
header('Location: https://informatica.ieszaidinvergeles.org:10050/PIA/Rekognition/process.php?file=' . $file . '&name=' . $name);
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
        //to see the message: $e->getMessage()
    } catch (S3Exception $e) {
        //to see the message: $e->getMessage()
    }
    return $result;
}