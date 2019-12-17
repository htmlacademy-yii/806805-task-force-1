<?php
require_once ('vendor/autoload.php');

use TaskForce\Fixtures\FileDataImporter2;

use TaskForce\Exs\FileFormatException;
use TaskForce\Exs\SourceFileException;

/* 
_tests-m3t2v2-file - 2версия (v2) Отличается от 1 тем, что теперь обход папки с файлами и ее чтение выполняет родитель \FilesystemIterator моего класса, 
каждый раз мы меняем с помощью myGetFileName($fileName) имя файла и импортируем файл в SQL-запрос

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
echo "Директория: ";
$path = __DIR__ . '/data';
print($path);

//2. Импортирование
// Запускаем один объект (работает)
$Loader = new FileDataImporter2($path);

// Запускаем итерацию объектов (работает)
// $fileName - перебирет все файлы в папке с помощью родителя \FilesystemIterator
foreach($Loader as $fileName) {

    try {
        // Круто! Можно задать конкретный файл (полное имя), или передавать файл при обходе \FilesystemIterator, при обходе также можно передать NULL.
        $Loader->myGetFileName(NULL);
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
    echo '<hr>';

}