<?php

ini_set('display_errors', 1);
ini_set('display_startup-errors', 1);
error_reporting(E_ALL);

// Encuentra todas las librerias que tengamos de manera automatica
require '../vendor/autoload.php';

// Importación de las librerías
use Dotenv\Dotenv;
use Aws\Rekognition\RekognitionClient;

// Para utilizar el archivo '.env'
$dotenv = Dotenv::createImmutable('/var/www/html/PIA/RekognitionPrueba');
$dotenv->load();

$newCoords = [];
if(isset($_GET['name'])) {
    $name = $_GET['name'];
    $coords = detectFaces($name);
    $newCoords = getFaceValues($coords);
}
// header('Content-Type: application/json; charset:utf-8');
echo json_encode($newCoords);
exit;

// FUNCIONES -------------------------------------------------------------------
function getFaceValues($data) {
    // var_dump($data);
    $faces = [];
    foreach($data['FaceDetails'] as $index => $value) {
        $face = [];
        $face['Width'] = $value['BoundingBox']['Width'];
        $face['height'] = $value['BoundingBox']['Height'];
        $face['Top'] = $value['BoundingBox']['Top'];
        $face['Left'] = $value['BoundingBox']['Left'];
        $face['low'] = $value['AgeRange']['Low'];
        $face['high'] = $value['AgeRange']['High'];
        $face['gender'] = $value['Gender']['Value'];
        $faces[] = $face; //[] en blanco, calcula la ultima posicion del array
    }
    return $faces;
}

function detectFaces($name) {
    try{
        $rekognition = new RekognitionClient([
            'version'     => 'latest',
            'region'      => 'us-east-1',
            'credentials' => [
                'key'    => $_ENV['aws_access_key_id'],
                'secret' => $_ENV['aws_secret_access_key'],
                'token'  => $_ENV['aws_session_token']
            ]
        ]);
        $result = $rekognition->DetectFaces(array(
            'Image' => [
                'S3Object' => [
                    'Bucket' => $_ENV['bucket_name'],
                    'Name' => $name,
                ],
            ],
           'Attributes' => ['ALL']
           )
        );
    } catch(Exception $e) {
        $result = false;
    }
    return $result;
}