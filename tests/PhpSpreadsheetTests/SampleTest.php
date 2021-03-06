<?php

namespace PhpOffice\PhpSpreadsheetTests;

class SampleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @dataProvider providerSample
     */
    public function testSample($sample)
    {
        // Suppress output to console
        $this->setOutputCallback(function () {
        });

        require $sample;
    }

    public function providerSample()
    {
        $skipped = [
            '07 Reader PCLZip', // Excel2007 cannot load file, leading to OpenOffice trying to and crashing. This is a bug that should be fixed
            '20 Read OOCalc with PCLZip', // Crash: Call to undefined method \PhpOffice\PhpSpreadsheet\Shared\ZipArchive::statName()
            '21 Pdf', // for now we don't have 3rdparty libs to tests PDF, but it should be added
        ];

        // Unfortunately some tests are too long be ran with code-coverage
        // analysis on Travis, so we need to exclude them
        global $argv;
        if (in_array('--coverage-clover', $argv)) {
            $tooLongToBeCovered = [
                '06 Largescale',
                '06 Largescale with cellcaching',
                '06 Largescale with cellcaching sqlite',
                '06 Largescale with cellcaching sqlite3',
                '13 CalculationCyclicFormulae',
            ];
            $skipped = array_merge($skipped, $tooLongToBeCovered);
        }

        $helper = new \PhpOffice\PhpSpreadsheet\Helper\Sample();
        $samples = [];
        foreach ($helper->getSamples() as $name => $sample) {
            if (!in_array($name, $skipped)) {
                $samples[$name] = [$sample];
            }
        }

        return $samples;
    }
}
