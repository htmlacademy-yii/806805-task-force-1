<?php

namespace ownsite\converting;

use ownsite\exs\FileSourceException;
use ownsite\converting\Converting;

class ConverterData extends ConvertingData implements ConverterUI
{
    public function getObjectData()
    {
        return $this;
    }

    public function getImportedData()
    {
        return $this->importedData;
    }

    public function import($fileNull): bool
    {
        return !empty($this->importedData);
    }

}
