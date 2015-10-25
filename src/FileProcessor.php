<?php

namespace Consigment;

final class FileProcessor
{
    public static function processFile($file, $structure)
    {
        if (!$fh = @fopen($file, 'r')) {
            die('Error opening file ' . $file);
        }

        $limit = 1000000000000000;
        $k     = 0;
        while (!feof($fh)) {
            $line = fgets($fh, 9999);

            $type = self::indentifyLineType($line);
            if (!$type) {
                continue;
            }
            if ($type != 'operation') {
                continue;
            }

            $parsed[] = self::parseLine($line, $structure[$type]);

            if ($k > $limit) {
                break;
            }
        }
        fclose($fh);
        flush();

        $csvData = self::parsedCollectionToCSV($parsed);

        $outputFilename = $file . '.csv';
        file_put_contents($outputFilename, $csvData);
    }

    public static function indentifyLineType($line)
    {
        $types = [
            '10' => 'fileHeader',
            '00' => 'remesaHeader',
            '01' => 'operation',
            '99' => 'commerce',
        ];

        $type = substr($line, 0, 2);
        if (!$type || !array_key_exists($type, $types)) {
            echo 'Invalid line type detected: ' . $type . PHP_EOL;

            return false;
        }

        // echo "line ".$line.' identified as '.$types[$type];
        return $types[$type];
    }

    public static function parseLine($line, $structure)
    {
        $parsed = [];
        foreach ($structure as $fieldName => $fieldLimits) {
            $length = $fieldLimits[0];

            $input  = substr($line, 0, $length);
            $output = $input;
            if (!empty($fieldLimits[1])) {
                $fieldFormat = $fieldLimits[1];

                if (is_array($fieldFormat)) {
                    $pattern = $fieldLimits[1][0];
                    $format  = $fieldLimits[1][1];

                    $output = preg_replace('/' . $pattern . '/', $format, $input);
                } else {
                    $expectedValue = $fieldFormat;
                    if ($input != $expectedValue) {
                        echo "Invalid value in field " . $fieldName . '. Found ' . $input . ', expected ' . $expectedValue . PHP_EOL;
                        break;
                    }
                }
            }

            $parsed[$fieldName] = $output;

            $line = substr($line, $length);
            // print_r($fieldName.'--->'.$parsed[$fieldName].'<--->'.$line);
        }

        return $parsed;
    }

    public static function parsedCollectionToCSV($collection)
    {
        $out = '';
        foreach ($collection as $key => $item) {
            if ($key == 0) {
                $out .= self::parsedItemToCsvTitle($item);
            }
            $out .= self::parsedItemToCsv($item);
        }

        return $out;
    }

    public static function parsedItemToCsv($parsedItem)
    {
        $csv = '';
        foreach (array_values($parsedItem) as $key => $val) {
            if ($key != 0) {
                $csv .= ';';
            }
            $csv .= '"' . $val . '"';
        }

        return $csv . PHP_EOL;
    }

    public static function parsedItemToCsvTitle($parsedItem)
    {
        $csv = '';
        foreach (array_keys($parsedItem) as $key => $val) {
            if ($key != 0) {
                $csv .= ';';
            }
            $csv .= '"' . $val . '"';
        }

        return $csv . PHP_EOL;
    }
}

