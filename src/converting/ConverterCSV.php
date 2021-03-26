<?php

namespace ownsite\converting;

use ownsite\exs\FileSourceException;
use ownsite\converting\Converting;

class ConverterCSV extends ConvertingFiles
{
    public function getObjectData()
    {
        return new importCSV();
    }
}
