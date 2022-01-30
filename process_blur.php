<?php

// Mostrar errores
ini_set('display_errors', 1);
ini_set('display_startup-errors', 1);
error_reporting(E_ALL);

$file = $_POST['file'];
$name = $_POST['name'];

$image1 = imagecreatefromjpeg($file);
$image2 = imagecreatefromjpeg($file);

for($i = 0; $i < 30; $i++){
    imagefilter($image1, IMG_FILTER_GAUSSIAN_BLUR); //apply repeated times    
}

foreach($_POST['x'] as $index => $x) {
    $y = $_POST['y'][$index];
    $w = $_POST['w'][$index];
    $h = $_POST['h'][$index];
    imagecopy($image2, $image1, $x, $y, $x, $y, $w, $h); //copy area
}

imagepng($image2, 'blur_' . $name, 0, PNG_NO_FILTER); //save new file
imagedestroy($image1);
imagedestroy($image2);

header('Location: ' . 'blur_' . $name);