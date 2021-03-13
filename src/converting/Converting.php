<?php

namespace ownsite\converting;

use ownsite\exs\FileSourceException;

abstract class Converting
{
    private $dataAsArray;

    public function getPathToSaveDefault()
    {

        return dirname(\Yii::getAlias('@app')) . '/data/exporting';
    }

    public function getNewFile()
    {

        return $this->pathToSave . '/' . $this->newName;
    }

    abstract public function getObjectData();

    public function importToArray()
    {
        $dataObject = $this->getObjectData();

        $dataObject->import($this->file ?? null);

        return $this->dataAsArray = $dataObject->getImportedData();
    }

    public function exportToArrfile()
    {
        $fileExt = pathinfo($this->newName, PATHINFO_EXTENSION);

        if ($fileExt === 'abc') {
            $this->newName = pathinfo($this->newName, PATHINFO_FILENAME) . '.php';
        } elseif ($fileExt !== 'php') {
            throw new FileSourceException("Расширение нового файла - $fileExt, необходимо php");
        }

        $dataToString = "<?php" . PHP_EOL;
        $dataToString .= "return [" . PHP_EOL;
        foreach ($this->dataAsArray as $row) {
            $dataToString .= "   [";
            foreach ($row as $key => $value) {
                if (is_array($value)) {
                    $value2 = implode(', ', $value);
                    $dataToString .= "'$key' => [$value2], ";
                } elseif (is_int($value)) {
                    $dataToString .= "'$key' => $value, ";
                } elseif ($value === null or $value === '') {
                    $dataToString .= "'$key' => null, ";
                } else {
                    $dataToString .= "'$key' => '$value', ";
                }
            }
            $dataToString .= "]," . PHP_EOL;
        }
        $dataToString .= '];';

        return !empty(file_put_contents($this->pathToSave . '/' . $this->newName, $dataToString));
    }

    public function exportToSqlfile()
    {
        $fileExt = pathinfo($this->newName, PATHINFO_EXTENSION);

        if ($fileExt === 'abc') {
            $this->newName = pathinfo($this->newName, PATHINFO_FILENAME) . '.sql';
        } elseif ($fileExt !== 'sql') {
            throw new FileSourceException("Расширение нового файла - $fileExt, необходимо sql");
        }

        $tableKeys = array_keys($this->dataAsArray[0]);
        $table = pathinfo($this->file, PATHINFO_FILENAME);

        $dataToString = "INSERT INTO $table (" . implode(', ', $tableKeys) . ") VALUES " . PHP_EOL;
        foreach($this->dataAsArray as $key => $row) {
            if (array_key_last($this->dataAsArray) === $key) {
                $dataToString .= "    ('" . implode("', '", $row) . "');" . PHP_EOL;
                continue;
            }
            $dataToString .= "    ('" . implode("', '", $row) . "')," . PHP_EOL;
        }

        return !empty(file_put_contents($this->pathToSave . '/' . $this->newName, $dataToString));
    }
}
