<?php

namespace App\Factory;

use App\Command\TransformXmlProductDataCommand;
use App\Transformer\CsvDataTransformer;
use App\Transformer\DataTransformerInterface;
use App\Transformer\ExcelDataTransformer;
use App\Transformer\JsonDataTransformer;
use App\Writer\XmlToCsvWriter;
use Exception;

class DataTransformerFactory {

    public static function create($format): DataTransformerInterface
    {
        switch ($format) {
            case TransformXmlProductDataCommand::FORMAT_JSON:
                return new JsonDataTransformer();
            break;

            case TransformXmlProductDataCommand::FORMAT_CSV:
                return new CsvDataTransformer(new XmlToCsvWriter());
            break;

            case TransformXmlProductDataCommand::FORMAT_EXCEL:
                return new ExcelDataTransformer(new XmlToCsvWriter());
            break;

            default:
                throw new Exception('Invalid format.');
        }
    }

}