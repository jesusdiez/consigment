#!/usr/local/bin/php
<?php

$structureInfo = [
    'fileHeader'   => [
        'type'        => [2, '10'],
        'processDate' => [10],
        'initialDate' => [10],
        'finalDate'   => [10],
        'padding'     => [179],
    ],
    'remesaHeader' => [
        'Tipo de registro' => [2, '00'],
        'Contrato'         => [15], // Número del contrato del comercio
        'Comercio'         => [10], // Número FUC del comercio
        'Cuenta'           => [20], // CCC de la cuenta del comercio
        'Oficina'          => [4], // Oficina gestora del comercio
        'Fecharoceso'      => [10], // DD-MM-AAAA]// Fecha del proceso de liquidación
        'FechaInicio'      => [10], // DD-MM-AAAA Fecha desde la que se informan operaciones
        'FechaFinal'       => [10], // DD-MM-AAAA Fecha hasta la que se informan operaciones
        'Relleno'          => [130],
    ],
    'operation'    => [
        'Tipo de registro'        => [2, '01'],
        'Valor'                   => [10], // DD-MM-AAAA]// Fecha del proceso de liquidación
        'Remesa'                  => [10],
        'Factura'                 => [12],
        'Oficina remesa'          => [4],
        'Tarjeta'                 => [16],
        'Relleno1'                => [4],
        'Marca de la tarjeta'     => [1],
        'Clase de tarjeta'        => [1],
        'Modalidad de pago'       => [1],
        'Entidad emisora tarjeta' => [1],
        'Fecha operacion'         => [16, ['(\d{2}-\d{2}-\d{4})(\d{2})(\d{2})(\d{2})', '$1 $2:$3:$4']],
        'Autorizacion'            => [6],
        'Tipo operacion'          => [2],
        'Captura operacion'       => [3],
        'Importe operacion'       => [11],
        'Signo operacion'         => [1],
        'Tasa descuento'          => [5],
        'Importe descuento'       => [9],
        'Signo descuento'         => [1],
        'Importe liquido'         => [13],
        'Signo liquido'           => [1],
        'TPV'                     => [13],
        'Relleno2'                => [38],
        'Divisa comercio'         => [3],
        'Numero de pedido'        => [12],
        'Coodigo razon'           => [4],
        'Relleno3'                => [2],
        'Importe original'        => [13],
        'Signo original'          => [1],
        'Divisa original'         => [3],
        'Relleno4'                => [1],
    ],
    'commerce'     => [
        'type'        => [2, '99'],
        'relleno'     => [25],
        'operaciones' => [9],
        'importe'     => [13],
        'signo'       => [1],
        'relleno'     => [161],
    ],
];

require('src/FileProcessor.php');

use Consigment\FileProcessor;

$files = [];
if (empty($argv[1])) {
    $files = glob('./*.txt');
    if (!count($files)) {
        die('File to parse undefined');
    } else {
        echo "Automatically selected every .txt file of this folder" . PHP_EOL;
    }
} else {
    $files = [$argv[1]];
}

foreach ($files as $file) {
    echo 'Process ' . $file . PHP_EOL;
    FileProcessor::processFile($file, $structureInfo);
}
echo "done!".PHP_EOL;
