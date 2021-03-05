<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Конвертер - arrSaver';
?>

<section class="registration__user">
    <h1>Конвертер - arr-saver</h1>

    <?php foreach ($result as $tableName=> $tabResult):?>
    <?= $tableName . ' - ' . $tabResult . '<br />'  ?>
    <?php endforeach; ?>

</section>
