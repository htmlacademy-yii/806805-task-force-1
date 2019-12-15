<?php
require_once ('vendor/autoload.php');

use TaskForce\Fixtures\FileDataImporter;
use TaskForce\Fixtures\FileDataMerger; 

use TaskForce\Exs\FileFormatException;
use TaskForce\Exs\SourceFileException;


/* 
Различия Созданные и Исходные таблицы и их поля в папке data
0 - categories - категории
1 - locations/cities(было) - города
2 - feedbacks/opinions(было) - мнение, оценка, рейтинг, отзыв. было - (add_time,point,desk)/(dt_add,rate,description)
3 - users-part2/profiles(было) - профиль пользователя. было - (address,bd,about,phone,skype)/(address,birth_date,about,phone,skype)
4 - users-part1/users(было) - пользователь. было - (email,name,password,dt_add)/(email,name,password,reg_time)
*/

function printPre($value) {
    print "<pre>"; print_r($value); print "</pre>"; 
}


$filesPath = __DIR__ . '/data';
$fileNo = $_GET['fileNo'] ?? NULL;
if($fileNo === '') {
    $fileNo = NULL;
}

$mergedFilesNo = [];
for($i=0; isset($_GET["file-" . $i]); $i++) {
    $mergedFilesNo[] = $_GET["file-$i"];
}

if(isset($_GET['delete'])) {
    unset($mergedFilesNo[$_GET['delete']]);
}

if(isset($_GET['add']) && $fileNo !== NULL) {
    $mergedFilesNo[] = '';
}



$Loader = new FileDataImporter($filesPath, $fileNo, $mergedFilesNo);
//$Merger = new FileDataMerger($filesPath);

echo "Директория: ";
print($Loader->getFilesPath());
//print($Merger->getFilesPath());

printPre($Loader->getFilesList());
//printPre($Merger->getFilesList());

$Loader->pageForma();

try {
    $buffer = $Loader->mergeFiles();
    $Loader->convertInSQL();
}
catch (FileFormatException $ex) {
    print 'EX1' . $ex->getMessage();
}
catch (SourceFileException $ex) {
    print 'EX2! ' . $ex->getMessage();
}