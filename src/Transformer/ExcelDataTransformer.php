<?php 

namespace App\Transformer;

use App\Writer\WriterInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelDataTransformer implements DataTransformerInterface {

    private WriterInterface $csvWriter;

    function __construct(WriterInterface $writer)
    {
        $this->csvWriter = $writer;
    }

    public function transform($inputData, $outputFilePath) : void {
        $spreadsheet = new Spreadsheet();

        $tmpFilePath = dirname($outputFilePath) . DIRECTORY_SEPARATOR . 'tmp.csv';
        $tmpFile = fopen($tmpFilePath, 'w');
        fputcsv($tmpFile, ['Product Name', 'Product Price'] ,',','"');
        $this->csvWriter->write($inputData, $tmpFile);

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();

        $reader->setDelimiter(',');
        $reader->setEnclosure('"');
        $reader->setSheetIndex(0);

        $spreadsheet = $reader->load($tmpFilePath);
        $writer = new Xlsx($spreadsheet);
        $writer->save($outputFilePath);

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        fclose($tmpFile);
        unlink($tmpFilePath);
    }

}