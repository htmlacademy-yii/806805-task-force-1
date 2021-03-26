<?php

namespace ownsite\converting;

use ownsite\exs\FileSourceException;
use ownsite\converting\Converting;

abstract class ConvertingFiles extends Converting
{
    /**
     * @param $file
     * полное имя файла, влияет на имя хранения по умолчанию
     * может быть file.csv, file.php (array), file.json
     * @param $newName
     * Расширение файла определяется методом экспорта
     * может быть .csv, .php (array), .sql, .json
     */
    public $file;
    public $pathToSave;
    public $newName;
    protected $importedData;

    public function __construct(string $file, string $pathToSave = null, string $newName = null)
    {
        $this->file = $file;
        if ($this->file !== null && !is_readable($this->file)) {
            Throw new FileSourceException("Файл не существует " . $this->file);
        }

        $this->pathToSave = $pathToSave ? $pathToSave : $this->getPathToSaveDefault();
        if (!is_dir($this->pathToSave)) {
            Throw new FileSourceException("Директория не существует " . $this->pathToSave);
        }

        $this->newName = $newName ? $newName : $this->getNewNameDefault();
    }

    public function getNewNameDefault() {

        return pathinfo($this->file, PATHINFO_FILENAME) . '.abc';
    }

    abstract public function getObjectData();
}
