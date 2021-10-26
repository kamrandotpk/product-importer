<?php 

namespace App\Transformer;

class JsonDataTransformer implements DataTransformerInterface {

    public function transform($inputData, $outputFilePath) : void {
        file_put_contents($outputFilePath, json_encode($inputData, JSON_PRETTY_PRINT));
    }

}