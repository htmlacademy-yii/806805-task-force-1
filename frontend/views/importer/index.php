<?php 

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

// Автозагрузчик в основной директории
require __DIR__ . '/../../../vendor/autoload.php';

/**
 * 1. CsvFileScaner родственник \FilesystemIterator - ищем файлы в директории $path с расширением csv, кроме файлов начинающихся символом "_"
 * 2. Csv2SqlConveter___v2_scaner - читает файл, поля являются ключами, а значения числа или функции формирования значений. 
 * - преобразует данные в запрос и создает файл sql
 * - сохраняет в первый раз, в следующих проходах идет перезапись
 * 3. $pathToSave - директория сохранения
 * 4. $filesArchive - названия полей, адрес файла, адрес сохранения по стандарту класса Csv2SqlConveter___v2_scaner
 * 5. 
 */
use dirSite\utilities\Fixtures\CsvFileScaner;
use dirSite\utilities\Fixtures\Csv2SqlConveter___v2_scaner;

function printpre($value) {
    print "<pre>"; print_r($value); print "</pre>"; 
}

$path = __DIR__ . '/../../../schemas/csvData';
$pathToSave = __DIR__ . '/../../../schemas/csv2sql';
$filesArchive = [];

$scaner = new CsvFileScaner($path);

foreach ($scaner as $file) {

    $fileParams = $scaner->getFileParams($pathToSave);
     if($fileParams) {
        $filesArchive[] = $scaner->makeFilesArchive();
    }
}

$csvFilesWithRules = $filesArchive;

foreach ($csvFilesWithRules as $file){

    $sql = Csv2SqlConveter___v2_scaner::getSqlFromCsv($file['path2file'], $file['value_map'], $file['table_name']);
    print_r($sql);
    print('<br><br>');
    // ! важно, это сохранение можно реализовать и в этом же классе.
    $saver = file_put_contents($file['path2save'], $sql);
    if($saver) {echo 'fire';}
}
