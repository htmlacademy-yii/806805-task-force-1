<?php
require_once ('vendor/autoload.php');

use TaskForce\Fixtures\FileDataImporter;

use TaskForce\Exs\FileFormatException;
use TaskForce\Exs\SourceFileException;

/* 
_tests-m3t2v1-form - Это сценарий с формой, в которой вводится номер файла из найденных в дирректории. Используется класс FileDataFormImporter
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

//2. Вводим номер файла в переменную
$fileNo = 1;

echo "Директория: ";
print($filesPath);

echo "<br>Список файлов: ";
$filesList = FileDataImporter::getFilesList($filesPath);
printPre($filesList);

//3. Импортирование

// Каждый файл директории
foreach ($filesList as $fileNo => $value) {

// Запускаем один объект
$Loader = new FileDataImporter($filesPath, $fileNo);

try {
    // Проверяем номер в папке и сохраняем полное имя выбранного файла
    $fileName = $Loader->getFileName();
    // Проверяем на чтение и существование, загружаем строки из файла в буфер-переменные
    $buffer = $Loader->importIntoBuffer();
    // Конвертируем переменные в SQL запрос
    $Loader->convertInSQL();

}
catch (FileFormatException $ex) {
    print '<br>EX1 ' . $ex->getMessage();
}
catch (SourceFileException $ex) {
    print '<br>EX2! ' . $ex->getMessage();
}

}