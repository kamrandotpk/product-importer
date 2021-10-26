<?php

namespace App\Writer;

class XmlToCsvWriter implements WriterInterface {

    public function write($dataToWrite, $outputFileHandle):void
    {
        $arr = [];
        foreach ($dataToWrite->children() as $data) {
            if (count($data->children()) === 0) {
                $arr[] = $data; 
            } else {
                $this->write($data, $outputFileHandle);
            }
        }
        fputcsv($outputFileHandle, $arr ,',','"');
    }

}