<?php

namespace dirSite\utilities\Fixtures;

use SplFileObject;

class Csv2SqlConveter___v2
{
    public static function getFile(string $fileName, $path)
    {
        return $file = new SplFileObject($path . $fileName);
    }

    public static function getSqlFromCsv(string $fileName, array $value_map, string $table_name, $path)
    {
        $file = self::getFile($fileName, $path);
        $file->setFlags(8);
        $headers = $file->current();
        $sql = [];
        foreach ($file as $row) {
            $lineMap = [];
            /** @var string $headers */
            if ($row != $headers && !in_array(null, $row)) {
                foreach ($value_map as $fieldName => $fieldValue) {
                    $lineMap[$fieldName] = is_int($fieldValue) ? $row[$fieldValue] : $fieldValue();
                }
                $sql[] = "INSERT INTO $table_name (" . implode(', ', array_keys($lineMap)) . ")
                 VALUES (" . implode(', ', array_map(function (&$value) {
                        return "'" . $value . "'";
                    }, $lineMap)) . ")" . ";";
            }
        }

        return $sql;
    }

}
