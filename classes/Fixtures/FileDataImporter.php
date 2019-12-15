<?php 

namespace TaskForce\Fixtures; 

use TaskForce\Exs\FileFormatException;
use TaskForce\Exs\SourceFileException;

class FileDataImporter 
{
    // Список файлов и их адрес.
    //Начнем с ситис

    /*** СВОЙСТВА ***/

    public $filesPath; // строка адрес папки с файл/файлы
    public $fileNames; // массив названий импортируемых файлов c помощью
    public $fileName;
    public $fileNo; // первый файл, определяется в методе import
    public $mergedFilesNo; 
    protected $fp;
    protected $header_data = [];
    protected $row_data = [];

    private $error = null;

    public function __construct (string $filesPath, ?string $fileNo, ?array $mergedFilesNo) {

        $this->filesPath = $filesPath; // директория файлов, папка со всеми файлами
        $this->fileNo = $fileNo; // первый выбранный файл в первом поле формы (кнопка выбрать)
        $this->mergedFilesNo = $mergedFilesNo; // массив с файлами которые будут добавлены в первый файл, должны содержать колонки с одинаковым колвом строк
    }

    public function getFilesPath(): ?string {
        return $this->filesPath;
    }

    public function getFileName(): ?string {
        return $this->fileName;
    }

    public function getFilesList(): array {
        return array_slice(scandir($this->filesPath), 2);
    }

    public function selectFileName($fileNo): BOOL {

        if(isset($this->getFilesList()[$fileNo])) {
            $this->fileName = $this->filesPath . '/' . $this->getFilesList()[$fileNo];         
            return TRUE;
        } 

        return FALSE;
    }

    public function pageForma (): void {
        $sum = count($this->getFilesList());
        $number = $this->fileNo;
        echo "
        <form method='GET' action='http://localhost/_tests-m3t2v1.php'>
        ";

        echo "
        Найдено $sum файл(ов). Введите номер для загрузки: 
        <br><input type='text' name='fileNo' value='$number'>
        <button type='submit' name='button' value='select'>Выбрать</button>
        ";

        if(count($this->mergedFilesNo)) {
            echo "<br>Объединение с файлами (добавление столбцов в 1ый файл)";
            foreach($this->mergedFilesNo as $key => $value) {
                echo "<br><input type='text' name='file-$key' value='$value'> ";
                echo "<button type='submit' name='delete' value='$key'>Удалить</button> 
                ";
            }

            echo "<br><br><button type='submit' name='button' value='select'>Слияние</button> ";
        }

        echo "<button type='submit' name='add' value='add'>Добавить</button> 
        </form>";
    } 

    public function mergeFiles() {
        $mergedFilesNo = array_filter($this->mergedFilesNo, function($value) {return  $value !== '' ?? $value;});
        array_unshift($mergedFilesNo, $this->fileNo);

        printPre($mergedFilesNo);

        if($mergedFilesNo !== array_unique($mergedFilesNo)) {
            throw new SourceFileException('Есть повторяющиеся файлы!');
        }

        $mergedFiles = [];
        foreach($mergedFilesNo as $key => $value) {
            $this->selectFileName($value);
            $mergedFiles[] = $this->fileName;
        }

        foreach($mergedFiles as $key => $value) {
            $header_data_before = $this->header_data;
            $row_data_before = $this->row_data;

            $this->importIntoBuffer($value);

            $this->header_data = array_merge($header_data_before, $this->header_data);

            if(!empty($row_data_before)) {

                if(count($row_data_before) !== count($this->row_data) ) {
                    throw new SourceFileException('Файлы не совместимы');
                }

                for($i=0; $i < count($row_data_before); $i++) {
                    $this->row_data[$i] = array_merge($row_data_before[$i], $this->row_data[$i]);
                }
            }           
        }

    }

    public function importIntoBuffer($fileName): void
    {
        if(!$this->selectFileName($this->fileNo)) {
            throw new SourceFileException('No enter the number');
        }

        echo "<br>Загрузка ... $fileName";

        if (!file_exists($fileName)) {
            throw new SourceFileException('Файл не существует - ' . $fileName);
        }

        $this->fp = fopen($fileName, 'r');
        if (!$this->fp) {
            throw new SourceFileException("Не удалось открыть файл на чтение");
        }

        $this->header_data = $this->getHeaderData();

        $lines = [];
        while ($line = $this->getNextLine()) {
            $lines[] = $line;
        }
        $this->row_data = $lines;

    }

    private function getNextLine(): ?array {
        $data = fgetcsv($this->fp, ',');
        if($data) {
            return $data;
        }

        return NULL;
    }

    private function getHeaderData():?array {
        rewind($this->fp);
        $data = fgetcsv($this->fp);
        return $data;
    }

    public function convertInSQL(): void {
        // ('city','latitude','longitude')

        $sql_head = "INSERT INTO " . pathinfo($this->fileName)['filename'] 
        . " <br>(" . trim(implode(",", $this->header_data)) . ")<br>" ;

        printPre($sql_head);

        $sql_values = "VALUES <br>";
        for ($i=1; $i < count($this->row_data); $i++) {
            $sql_values .=  "('" . implode("','", $this->row_data[$i-1]) . "'),<br>";
        }
        $sql_values .= "('" . implode("','", array_pop($this->row_data)) . "');";

        printPre($sql_values);

    }

}