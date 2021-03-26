<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Конвертеры';
?>

<section class="registration__user">
    <h1>Конвертеры</h1>

<?php foreach($test as $row): ?>
    <?php print_r($row) ?>
    <?= '<br>' ?>
<?php endforeach; ?>
</section>
