<?php 
use yii\helpers\html;

print html::encode($mess);
print '<br>';
print '<pre>';

print_r($task);

print '</pre>';