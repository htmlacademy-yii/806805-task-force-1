<?php

namespace frontend\Fixtures;

use SplFileObject;

class CsvFileScaner extends \FilesystemIterator
{
    //1. Класс сканирует переданную директорию с помощью родителя, директория передается в конструктор родителя
    //2. Класс формирует массив из параметров файла для конвертированя, см ниже

    /* Пример параметров файла
    array(
        [
        'path2file' => 'C:\\inetpub\wwwroot\TaskForce/data/_categories.csv', // полное имя файла в директории
        'path2save' => 'schemas/csv/categories.sql', // директория для сохранения и имя файла
        'table_name' => 'categories', // название таблицы, совпадает с именем файла sql 
        'value_map' => [ // ассоциативный массив с названием полей ввиде ключей
            'name' => 0,
            'symbol' => 1
        ]
    )
    */

    /* ***СВОЙСТВА*** */

    public $file;           // полное имя файла в директории
    public $pathToSave;     // директория сохранения
    public $tableName;       // имя таблицы, совпадает с именем файла без расширения
    public $sqlHeaders;        // ассоциативный массив с названием полей ввиде ключей


    /* ***МЕТОДЫ*** */


    public function getFileParams ($pathToSave) {

        if(parent::valid() && parent::getExtension() === 'csv'
            && strpos(parent::getFilename(), '_') !== 0) {

            $this->file = parent::getPathName();
            $this->pathToSave = $pathToSave . '/' . parent::getBasename('.csv') . '.sql';
            $this->tableName = parent::getBasename('.csv');

            $file = self::getFile ($this->file);
            $file->setFlags(8);
            $this->sqlHeaders = array_flip($file->current());

            return true;
        }
        return false;
    }

    public static function getFile ($file) {
        return new SplFileObject($file);
    }

    public function makeFilesArchive () {
        $archive = [];
        $archive['path2file'] = $this->file;
        $archive['path2save'] = $this->pathToSave;
        $archive['table_name'] = $this->tableName;
        $archive['value_map'] = $this->sqlHeaders;

        return $archive;
    }

}