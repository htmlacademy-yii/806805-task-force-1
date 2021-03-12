<?php

namespace ownsite\converting;

use ownsite\converting\Converting;
use ownsite\exs\FileFormatException;
use ownsite\exs\FileSourceException;

class importCSV implements ConverterUI 
{
    private $fileObject;
    private $importedData;

    public function getImportedData()
    {
        return $this->importedData;
    }

    public function import($file): bool
    {
        if (!is_readable($file)) {
            Throw new FileSourceException("Файл не существует " . $file);
        }

        if (pathinfo($file, PATHINFO_EXTENSION) !== 'csv') {
            Throw new FileFormatException("Расширение файла должно быть " . "csv");
        }

        $this->fileObject = new \SplFileObject($file, 'r');

        $headers = $this->getHeaderData();

        $values = [];
        foreach ($this->getLineData() as $line) {
            $values[] = $line;
        }

        $this->importedData = array_map(function($row) use($headers) {
            return array_combine($headers, $row);
        }, $values);

        return !empty($this->importedData);
    }

    private function getHeaderData(): array
    {
        $this->fileObject->rewind();
        
        return $this->fileObject->fgetcsv();
    }

    private function getLineData() 
    {
        while(!$this->fileObject->eof()) {
            yield $this->fileObject->fgetcsv();
        }
    }
}