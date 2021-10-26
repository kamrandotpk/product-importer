<?php 

namespace App\Transformer;

use App\Writer\WriterInterface;

class CsvDataTransformer implements DataTransformerInterface {

    private WriterInterface $csvWriter;

    function __construct(WriterInterface $writer)
    {
        $this->csvWriter = $writer;
    }

    public function transform($inputData, $outputFilePath) : void {
        $outputFile = fopen($outputFilePath, 'w');
        fputcsv($outputFile, ['Product Name', 'Product Price'] ,',','"');
        $this->csvWriter->write($inputData, $outputFile);
        fclose($outputFile);
    }

}