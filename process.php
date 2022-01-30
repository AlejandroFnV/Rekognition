<!--OBTENCIÓN DE VARIABLES ---------------------------------------------------->
<?php
    require 'vendor/autoload.php';

    if(isset($_GET['file']) && isset($_GET['name'])) {
        $name = $_GET['name'];
        $file = $_GET['file'];    
    } else {
        header('Location: https://informatica.ieszaidinvergeles.org:10050/PIA/Rekognition');
        exit;
    }
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Document</title>
        
        <link rel="stylesheet" href="https://unpkg.com/jcrop/dist/jcrop.css">
        <script src="https://unpkg.com/jcrop"></script>
        
    </head>
    <body>
        <img src="<?php echo $file ?>" alt="Imagen subida" id="imagen">
        <form action="process_blur.php" method="post" id="fblur">
            <input type="hidden" name="file" id="file" value="<?php echo $file ?>" />
            <input type="hidden" name="name" id="name" value="<?php echo $name ?>" />
            <input type="submit" value="Process" />
        </form>
        <script src="js/service.js"></script>
    </body>
</html>