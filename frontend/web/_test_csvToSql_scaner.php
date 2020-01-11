<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/../../common/config/bootstrap.php';
require __DIR__ . '/../config/bootstrap.php';


use frontend\Fixtures\CsvFileScaner;
//use Yii\Fixtures\CsvFileScaner;
use frontend\Fixtures\Csv2SqlConveter___v2_scaner;

function printpre($value) {
    print "<pre>"; print_r($value); print "</pre>"; 
}

$path = __DIR__ . '/data';
$pathToSave = __DIR__ . '/schemas/csv2sql';
$filesArchive = []; // Архив для файлов по стандарту класса Csv2SqlConveter___v2_scaner

// Итерация, родственник \FilesystemIterator
$scaner = new CsvFileScaner($path);

foreach ($scaner as $file) {

    // Итерируются все файлы, поэтому проходов будет столько сколько файлов, 
    $fileParams = $scaner->getFileParams($pathToSave);
    
    // Поэтому если $fileParams вернет True значит нам подходит файл и мы записываем его в архив
    if($fileParams) {
        $filesArchive[] = $scaner->makeFilesArchive();
    }
}

// все данные о том, где содержится входной файл, имя выходного файла и правила формирования дополнительных полей.
$csvFilesWithRules = $filesArchive;

foreach ($csvFilesWithRules as $file){

    // получаем sql
    // это всего лишь прототип, можно еще под себя переделать внутренность метода.
    $sql = Csv2SqlConveter___v2_scaner::getSqlFromCsv($file['path2file'], $file['value_map'], $file['table_name']);
    printPre($sql);
    // sql получен, нужно просто сохранить исопльзуя file_put_contest например
    // ! важно, это сохранение можно реализовать и в этом же классе.
    // сохраняет в первый раз, в следующих проходах идет перезапись
    $saver = file_put_contents($file['path2save'], $sql);
    if($saver) {echo 'fire';}
}
