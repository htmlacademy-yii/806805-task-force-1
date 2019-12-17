<?php 

namespace TaskForce\Fixtures; 

use TaskForce\Exs\FileFormatException;
use TaskForce\Exs\SourceFileException;

class FileDataImporter2 extends \FilesystemIterator
{

    /*** СВОЙСТВА ***/
    public $fileName; // полное имя выбранного файла, получаем из сценария, обращаясь 
    private $fp; // подключение к файлу
    private $header_data = []; // первая строка выбранного файла, буфер-переменные
    private $row_data = []; // все строки выбранного файла, буфер-переменные

    private $error = null; // Не использовалась, возможно для SplFileObject для парсинга csv

    //Конструктор убран - теперь директорию получает родитель и обработает файлы \FilesystemIterator

    public function myGetFileName (?string $fileName) {
        $this->header_data = [];  
        $this->row_data = []; // Чистка, иначе прибавятся строки предыдущих файлов
        $this->fileName = $fileName ?? parent::current(); // Если задать имя файла то загрузится конкретный файл, иначе делается итерация
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
    // пример ('city','latitude','longitude'),
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

    public function next() {
        parent::next();
    }
}