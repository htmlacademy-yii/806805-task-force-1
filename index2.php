<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
require_once ('vendor/autoload.php');

use TaskForce\Fixtures\Csv2SqlConveter___v2;

function printPre($value) {
    print "<pre>"; print_r($value); print "</pre>"; 
}

// все данные о том, где содержится входной файл, имя выходного файла и правила формирования дополнительных полей.
$csvFilesWithRules = [
    [
        'path2file' => '/data/categories.csv',
        'path2save' => 'csv2sql/categories.sql',
        'table_name' => 'categories',
        'value_map' => [
            'name' => 0,
            'symbol' => 1
        ],
    ],
    [
        'path2file' => '/data/locations.csv',
        'path2save' => 'csv2sql/locations.sql',
        'table_name' => 'categories',
        'value_map' => [
            'city' => 0,
            'latitude' => 1,
            'longitude' => 2
        ],
    ]

];

foreach($csvFilesWithRules as $file){

    // получаем sql
    // это всего лишь прототип, можно еще под себя переделать внутренность метода.
    $sql = Csv2SqlConveter___v2::getSqlFromCsv($file['path2file'], $file['value_map'], $file['table_name'], __DIR__);
    printPre($sql);
    // sql получен, нужно просто сохранить исопльзуя file_put_contest например
    // ! важно, это сохранение можно реализовать и в этом же классе.
    // сохраняет в первый раз, в следующих проходах идет перезапись
    $saver = file_put_contents($file['path2save'], $sql);
    if($saver) {echo 'fire';}
}
