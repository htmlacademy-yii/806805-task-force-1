<?php 

namespace TaskForce\Fixtures; 

use TaskForce\Exs\FileFormatException;
use TaskForce\Exs\SourceFileException;

class FileDataImporter 
{

    /*** СВОЙСТВА ***/

    public $filesPath; // директория/папка с файлами
    public $fileNo; // номер в директротии первый файл
    public $fileName; // полное имя выбранного файла
    private $fp; // подключение к файлу
    private $header_data = []; // первая строка выбранного файла, буфер-переменные
    private $row_data = []; // все строки выбранного файла, буфер-переменные

    private $error = null;

    public function __construct (string $filesPath, ?int $fileNo) {

        $this->filesPath = $filesPath; // директория файлов, папка со всеми файлами
        $this->fileNo = $fileNo; // номер выбранный файл
    }

    // Получение имен файлов в директории
    public static function getFilesList($filesPath): ?array {
        return array_slice(scandir($filesPath), 2) ?? NULL;
    }

    // Название файла
    public function getFileName(): ?string {
              
        // Проверяем, что файл существует в директории
        if(!isset(self::getFilesList($this->filesPath)[$this->fileNo])) {
            throw new SourceFileException('Wrong file number!');
        }

        return $this->fileName = $this->filesPath . '/' . self::getFilesList($this->filesPath)[$this->fileNo] ?? NULL;
    }

    // Проверка каждого файла, чтение, отправка в буфер-переменные
    public function importIntoBuffer(): void
    {
        echo "<br>Загрузка ... " . $this->fileName;

        if (!file_exists($this->fileName)) {
            throw new SourceFileException('Файл не существует - ' . $this->fileName);
        }

        $this->fp = fopen($this->fileName, 'r');
        if (!$this->fp) {
            throw new SourceFileException("Не удалось открыть файл на чтение");
        }

        $this->header_data = $this->getHeaderData();

        foreach ($this->getNextLine() as $line) {
            $this->row_data[] = $line;
        }

    }

    private function getNextLine(): ?iterable {

        while(($data = fgetcsv($this->fp, ',')) !== FALSE) {
            yield $data;
        }
        return NULL;
    }

    private function getHeaderData():?array {
        rewind($this->fp);
        $data = fgetcsv($this->fp);
        if (!$data) {
            throw new FileFormatException("Заголовки не верны или отсутствуют");
        }
        return $data;
    }

    // Печатать SQL код файлов
    // пример ('city','latitude','longitude')
    public function convertInSQL(): void {

        print('<br><br>Конвертирование из буфера в SQL-query: ');

        $sql_head = "INSERT INTO " . pathinfo($this->fileName)['filename'] 
        . " <br>(" . trim(implode(",", $this->header_data)) . ")<br>" ;

        printPre($sql_head);

        $sql_values = "VALUES <br>";
        for ($i=0; $i < count($this->row_data) - 1; $i++) {
            $sql_values .=  "('" . implode("','", $this->row_data[$i]) . "'),<br>";
        }
        $sql_values .= "('" . implode("','", array_pop($this->row_data)) . "');";

        printPre($sql_values);
    }

}