<?php

/* @var $this \yii\web\View */
/* @var $content string */

use frontend\assets\appAsset;
use frontend\assets\mainAsset;
// use yii\bootstrap\Nav;
// use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

appAsset::register($this);
mainAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>"><!-- ??? не изучено -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body>
<?php $this->beginBody() ?>
<div class="table-layout">

    <!-- Хедер -->
    <?php require_once __DIR__ . '/header-main.php'?>

    <!-- Контент  -->
    <main class="page-main">
        <div class="main-container page-container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>
            <?= $content ?><!-- Представление контент  -->
        </div>
    </main>

    <!-- футер  -->
    <?php require_once __DIR__ . '/footer.php'?>
    
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
