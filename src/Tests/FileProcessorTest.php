<?php

namespace Consigment\Tests;

class RemesaFileProcessorTest extends \PHPUnit_Framework_TestCase
{
    public function test_line_type_is_identified()
    {
        $aOperationLine = "0121-04-201545111079993487741253  50294909XXXXXXXX0846    VXDN20-04-201500312431256005ON 00000001000+00005000000002+0000000000998+0032724325901                                      EUR001407642293      0000000000000+                                  \n";

        $type = RemesaFileProcessor::indentifyLineType($line);

        $this->assert('operation', $type);
    }
}
