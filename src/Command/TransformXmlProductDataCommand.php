<?php

namespace App\Command;

use App\Factory\DataTransformerFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TransformXmlProductDataCommand extends Command
{
    protected static $defaultName = 'app:transform-xml-product-data';
    protected static $defaultDescription = 'This command transforms the provided XML product data into JSON, Excel or CSV format';

    const ARGUMENT_INPUT_FILE_PATH = 'product-xml-data-file-path';
    const OPTION_OUTPUT_FORMAT = 'output-format';
    const FORMAT_JSON = 'json';
    const FORMAT_CSV = 'csv';
    const FORMAT_EXCEL = 'xlsx';

    protected function configure(): void
    {
        $this
            ->addArgument(self::ARGUMENT_INPUT_FILE_PATH, InputArgument::REQUIRED, 'Absolute path of the input file containing XML product data')
            ->addOption(self::OPTION_OUTPUT_FORMAT, null, InputOption::VALUE_REQUIRED, 'Output format (xlsx, csv or json)', 'json')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $inputFilePath = $input->getArgument(self::ARGUMENT_INPUT_FILE_PATH);
        $outputFormat = $input->getOption(self::OPTION_OUTPUT_FORMAT);
        
        if (!file_exists($inputFilePath)) {
            $io->error(sprintf('No file found on the path you provided: %s', $inputFilePath));
            return Command::INVALID;
        }

        if (!in_array($outputFormat, [self::FORMAT_JSON, self::FORMAT_CSV, self::FORMAT_EXCEL])) {
            $io->error(sprintf('Invalid output format "%s". Valid formats are: %s, %s and %s', $inputFilePath, self::FORMAT_JSON, self::FORMAT_CSV, self::FORMAT_EXCEL));
            return Command::INVALID;
        }

        $xmlProductData = @simplexml_load_file($inputFilePath);

        if (!$xmlProductData) {
            $io->error(sprintf('The file "%s" is not a valid XML file', $inputFilePath));
            return Command::INVALID;
        }

        $pathInfo = pathinfo($inputFilePath);
        $outputFilePath = $pathInfo['dirname'] . DIRECTORY_SEPARATOR . $pathInfo['filename'] . '.' . $outputFormat;
        DataTransformerFactory::create($outputFormat)->transform($xmlProductData, $outputFilePath);

        $io->success(sprintf('Products XML transformed successfully. Output file available at: %s', $outputFilePath));

        return Command::SUCCESS;
    }
}
