<?php
    include "lib/phpqrcode/qrlib.php";

    $dir = './temp/';
    $codgProd = 'abcd1234';

    //en caso de que la carpeta no exista, crearla
    if(!file_exists($dir))
        mkdir($dir);

    $filename = $dir.$codgProd.'.png';

    $tamanio = 10;
    $level = 'M'; //tipo de precisión
    $frameSize = 2; //marco en blanco
    

    QRcode::png($codgProd, $filename, $level, $tamanio, $frameSize);

    echo '<img src="'.$filename.'" />';
?>