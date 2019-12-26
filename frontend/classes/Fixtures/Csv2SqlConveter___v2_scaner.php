<?php

namespace frontend\classes\Fixtures;

use SplFileObject;

class Csv2SqlConveter___v2_scaner
{
    public static function getFile(string $file)
    {
        return $file = new SplFileObject($file);
    }

    public static function getSqlFromCsv(string $file, array $value_map, string $table_name)
    {
        $file = self::getFile($file);
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
