<?php

namespace App\Transformer;

interface DataTransformerInterface {

    public function transform($inputData, $outputFilePath):void;
    
}