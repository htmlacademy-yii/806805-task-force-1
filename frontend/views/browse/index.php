<?php 
use yii\helpers\html;

print html::encode($messTaskId);
print '<br>';
print '<pre>';
print_r($tasks);
print '</pre>';