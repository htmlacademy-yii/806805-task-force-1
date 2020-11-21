<?php

use yii\helpers\Html;
// use yii\helpers\Url;
use yii\widgets\ActiveForm;
// use yii\widgets\ActiveField; // Не используем

$this->title = 'Регистарция (Верстка singup.html)';
?>

<!-- Контент singup.html -->
<section class="registration__user">
    <h1>Регистрация аккаунта</h1>
    <div class="registration-wrapper">
        <!-- Форма -->
        <?php 
        $form = ActiveForm::begin([
            'id' => 'signup-form',

            'errorCssClass' => 'input-danger',
            'validationStateOn' => 'input',

            'ajaxDataType' => 'json',
            'enableAjaxValidation' => true,
            'enableClientValidation' => false,
            'validateOnSubmit' => true,
            'validateOnChange' => true,
            'validateOnType' => false,
            'validateOnBlur' => false,

            'options' => [
                'class' => 'registration__user-form form-create',
            ],
            'fieldConfig' => [
                'template' => "{label}\n{input}\n<span>{error}{hint}</span>",
                'options' => ['tag' => false],
                'errorOptions' => ['tag' => null, 'class' => false],
                'hintOptions' => ['tag' => null, 'class' => false],
            ],
        ]);
        ?>
            <!-- Поле Электронная почта, тип многострочное -->
            <?php 
            echo $form->field($formModel, 'email')
                ->textarea([
                    'class' => 'input textarea',
                    'rows' => 1,
                    'placeholder' => 'name@mail.ru',
                    'autofocus' => true
                ]);
            ?>
            <!-- Поле Имя, тип многострочное -->
            <?php
            echo $form->field($formModel, 'full_name')
                ->textarea([
                    'class' => 'input textarea',
                    'rows' => 1,
                    'placeholder' => 'Райская Ева',
                ]);
            ?>
            <!-- Поле город, селект -->
            <?php
            echo $form->field($formModel, 'location_id')
                ->dropDownList($formModel->getAttributeItems('location_id'), [
                    'prompt' => 'Выберите город',
                    'class' => 'multiple-select input town-select registration-town',
                    'size' => 1,
                ]);
            ?>
            <!-- Поле пароль, инпут пароль -->
            <?php
            echo $form->field($formModel, 'password')
                ->passwordInput(['class' => 'input textarea']);
            ?>
            <?=Html::submitButton('Cоздать аккаунт', ['class' => 'button button__registration'])?>
        <?php ActiveForm::end();?>
        <!-- /Форма -->
    </div>
</section>
<!-- /Контент singup.html -->
