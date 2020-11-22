<?php

/* @var $this \yii\web\View */
/* @var $content string */

// use common\widgets\Alert;
use frontend\assets\mainAsset;
// use yii\bootstrap\Nav;
// use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\helpers\Url;
// use yii\widgets\Breadcrumbs;

mainAsset::register($this);
?>

<?php $this->beginPage()?>
<!DOCTYPE html>
<html lang="<?=Yii::$app->language?>">

<head>
    <meta charset="<?=Yii::$app->charset?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags()?><!-- Оставляем код ??? Не изучено-->
    <title><?=Html::encode($this->title)?></title>
    <?php $this->head()?>
</head>

<body class="landing">
<?php $this->beginBody()?>
<div class="table-layout">

    <!-- Хедер -->
    <?php require_once __DIR__ . '/header-start.php';?>

    <main>
        <div class="landing-container">
            <?=$content?><!-- Представление контент  -->
        </div>
    </main>

    <!-- футер  -->
    <?php require_once __DIR__ . '/footer.php';?>

</div>

<div class="overlay"></div>
<script src="js/main.js"></script>

<?php $this->endBody()?>
</body>
</html>
<?php $this->endPage()?>
