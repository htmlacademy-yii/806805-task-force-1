<?php
require_once ('vendor/autoload.php');

use TaskForce\Fixtures\FileDataFormImporter;

use TaskForce\Exs\FileFormatException;
use TaskForce\Exs\SourceFileException;

/* 
_tests-csvToSql-form.php
Это сценарий с формой, в которой вводится номер файла из найденных в дирректории. Используется класс FileDataFormImporter
Можно добавить несколько полей для слияния/объединения файлов с одинаковым количеством строк, те добавить в 1ый файл столбцы из 2го

Различия Созданные и Исходные таблицы и их поля в папке data
0 - categories - категории
1 - locations/cities(было) - города
2 - feedbacks/opinions(было) - мнение, оценка, рейтинг, отзыв. было(dt_add,rate,description)/стало(add_time,point,desk)
3 - users-part2/profiles(было) - профиль пользователя. было(address,bd,about,phone,skype)/стало(address,birth_date,about,phone,skype)
4 - users-part1/users(было) - пользователь. было(email,name,password,dt_add)/стало(email,name,password,reg_time)
5 - offers/replies(было) - тоже что и opinions, не использовал!!!
6 - task - задания. было(dt_add,category_id,description,expire,name,address,budget,lat,long)/стало(add_time,id_category,description,end_date,address,price,latitude,longitude)
*/

function printPre($value) {
    print "<pre>"; print_r($value); print "</pre>"; 
}

//1. Вводим директорию, поное имя
$filesPath = __DIR__ . '/data';

//2. Определяем заполненность поля первого файла
$fileNo = $_GET['fileNo'] ?? NULL;
if($fileNo === '') {
    $fileNo = NULL;
}

//3. Определяем заполненность полей для сливания/объединения
$mergedFilesNo = [];
for($i=0; isset($_GET["file-" . $i]); $i++) {
    $mergedFilesNo[] = $_GET["file-$i"];
}

//4. Добавляем поле для сливания - 2ой и более файлы, если первый файл не пуст
if(isset($_GET['add']) && $fileNo !== NULL) {
    $mergedFilesNo[] = '';
}

//5. Удаляем поле по ключу, присвоенном в форме
if(isset($_GET['delete'])) {
    unset($mergedFilesNo[$_GET['delete']]);
}

//6. Запускаем один объект при каждом обновлении страницы. Нажми на кнопку получишь результат)))
$Loader = new FileDataFormImporter($filesPath, $fileNo, $mergedFilesNo);

echo "Директория: ";
print($Loader->getFilesPath());

printPre($Loader->getFilesList());

// Показываем поля формы после обработки файлов
$Loader->pageForma();

try {
    $buffer = $Loader->mergeFiles();
    $Loader->convertInCSV();
    print '<br><hr>';
    $Loader->convertInCSV2();
    print '<br><hr>';
    $Loader->convertInSQL();
}
catch (FileFormatException $ex) {
    print '<br>EX1 ' . $ex->getMessage();
}
catch (SourceFileException $ex) {
    print '<br>EX2! ' . $ex->getMessage();
}
