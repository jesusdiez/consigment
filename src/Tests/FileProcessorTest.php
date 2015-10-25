<?php

namespace Consigment\Tests;

use Consigment\FileProcessor;

class FileProcessorTest extends \PHPUnit_Framework_TestCase
{
    public function test_line_type_is_identified()
    {
        $aOperationLine = "0121-04-201545111079993487741253  50294909XXXXXXXX0846    VXDN20-04-201500312431256005ON 00000001000+00005000000002+0000000000998+0032724325901                                      EUR001407642293      0000000000000+                                  \n";

        $type = FileProcessor::indentifyLineType($aOperationLine);

        $this->assertEquals('operation', $type);
    }

    public function test_line_is_parsed()
    {
        $aOperationLine = "0121-04-201545111079993487741253  50294909XXXXXXXX0846    VXDN20-04-201500312431256005ON 00000001000+00005000000002+0000000000998+0032724325901                                      EUR001407642293      0000000000000+                                  \n";
        $aLineStructure = [
            'Tipo de registro' => [2, '01'],
            'Valor'            => [10],
            'Remesa'           => [10],
            'Factura'          => [12],
        ];
        $parsed = FileProcessor::parseLine($aOperationLine, $aLineStructure);

        $this->assertEquals('21-04-2015', $parsed['Valor']);
    }
}
