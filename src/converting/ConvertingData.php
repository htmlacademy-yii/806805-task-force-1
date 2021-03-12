<?php

namespace ownsite\converting;

use ownsite\exs\FileSourceException;
use ownsite\converting\Converting;

abstract class ConvertingData extends Converting
{
    /**
     * @param $importedData
     * источкик - готовые данные в формате массива
     * @param $newName
     * Расширение файла определяется методом экспорта
     * может быть .csv, .php (array), .sql, .json
     */
    public $pathToSave;
    public $newName;
    protected $importedData;

    public function __construct(array $importedData, string $pathToSave = null, string $newName)
    {
        $this->importedData = $importedData;

        $this->pathToSave = $pathToSave ? $pathToSave : $this->getPathToSaveDefault();
        if (!is_dir($this->pathToSave)) {
            Throw new FileSourceException("Директория не существует " . $this->pathToSave);
        }

        $this->newName = $newName;
    }

    abstract public function getObjectData();
}
