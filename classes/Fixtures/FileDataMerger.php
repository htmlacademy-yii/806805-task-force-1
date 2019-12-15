<?php 

namespace TaskForce\Fixtures; 

use TaskForce\Exs\FileFormatException;
use TaskForce\Exs\SourceFileException;

class FileDataMerger extends FileDataImporter 
{

    public $fileNameList; // первый файл, определяется в методе import

    public function selectFileName(): BOOL {

        if(parent::selectFileName()) {
            $number2 = $_GET['fileN2'] ?? NULL;
            if($number2 === '') {
                $number2 =  NULL;
            }

            echo $queryLine = http_build_query($_GET);
            echo parse_url($url, PHP_URL_QUERY);
            
            if($_GET['button'] !== 'merge') {
                echo "
                    <br>
                    <form method='GET' action='http://localhost/_tests-m3t2v1.php'>
                    Объединение файлов - добавление столбцов в первый файл
                    <button type='submit' name='button' value='merge'>Объединить</button>";
            }

            if($_GET['button'] == 'merge') {
                echo "
                <input type='text' name='fileN2' value='$number2'>
                <button type='submit' name='button' value='merge'>Объединить</button>
                ";
            }

            echo "</form>";

            if($number !== NULL && $number + 1 <= $sum) {
                $this->fileName = $this->filesPath . '/' . $this->fileNames[(int) $number];         
                print 'Полное имя файла: ' . $this->fileName;
                print '<br> <br>Загрузка ... <br><br>';
                return TRUE;
            } 
        }

    }
}