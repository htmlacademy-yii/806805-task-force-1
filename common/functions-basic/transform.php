<?php

namespace common\functions\basic\transform;

/** 
 * Специальные символы для полнотекстового поиска удаляются из строки поиска
 * Словам добавляется в конце специальный символ * в логическом режиме
 * Полнотексовый поиск выполняется правильно только в соответствии с первыми буквами слова
*/
function prepareLogicSearch(string $search)
{
    $symbol = ['+', '-', '*', '<', '>', '~', '@', '(', ')', '"', '"'];
    $saveSearch = trim(str_replace($symbol, ' ', $search));
    $words = array_filter(explode(' ', $saveSearch));
    $logicWords = array_map(function ($value) {return $value . '*';}, $words);
    
    return implode(' ', $logicWords);
}
