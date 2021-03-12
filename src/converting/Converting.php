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

    public function getDataAsArray()
    {
        $dataObject = $this->getObjectData();

        $dataObject->import($this->file ?? null);

        return $this->dataAsArray = $dataObject->getImportedData();
    }

    public function exportToArrfile()
    {

        $fileExt = pathinfo($this->newName, PATHINFO_EXTENSION);

        if ($fileExt === 'abc') {
            $this->newName = pathinfo($this->file, PATHINFO_FILENAME) . '.php';
        } elseif ($fileExt !== 'php') {
            throw new FileSourceException("Расширение нового файла - $fileExt, метод php");
        }

        $arrToString = "<?php" . PHP_EOL;
        $arrToString .= "return [" . PHP_EOL;
        foreach ($this->dataAsArray as $row) {
            $arrToString .= "   [";
            foreach ($row as $key => $value) {
                if (is_array($value)) {
                    $value2 = implode(', ', $value);
                    $arrToString .= "'$key' => [$value2], ";
                } elseif (is_int($value)) {
                    $arrToString .= "'$key' => $value, ";
                } elseif ($value === null or $value === '') {
                    $arrToString .= "'$key' => null, ";
                } else {
                    $arrToString .= "'$key' => '$value', ";
                }
            }
            $arrToString .= "]," . PHP_EOL;
        }
        $arrToString .= '];';

        return !empty(file_put_contents($this->pathToSave . '/' . $this->newName, $arrToString));
    }
}
