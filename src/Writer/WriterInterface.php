<?php

namespace App\Writer;

interface WriterInterface {
    public function write($data, $outputFileHandle):void;
}