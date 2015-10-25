#!/usr/local/bin/php
<?php

require(__DIR__ . '/../vendor/autoload.php');

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

$structureInfo = require(__DIR__ . '/config/bank_pos_payment.php');

foreach ($files as $file) {
    echo 'Process ' . $file . PHP_EOL;
    FileProcessor::processFile($file, $structureInfo);
}
echo "done!" . PHP_EOL;
