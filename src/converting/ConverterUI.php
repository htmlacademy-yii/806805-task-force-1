<?php

namespace ownsite\converting;

interface ConverterUI
{
    public function getImportedData();

    public function import(?string $file);
}