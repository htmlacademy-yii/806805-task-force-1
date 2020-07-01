<?php

namespace common\functions\basic\transform;

function prepareLogicSearch(string $search)
{
    $symbol = ['+', '-', '*', '<', '>', '~', '@', '(', ')', '"', '"'];
    $saveSearch = trim(str_replace($symbol, ' ', $search));
    $words = array_filter(explode(' ', $saveSearch));
    $logicWords = array_map(function ($value) {return $value . '*';}, $words);
    
    return implode(' ', $logicWords);
}
