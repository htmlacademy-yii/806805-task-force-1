<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
// use yii\widgets\ActiveField; // Не используем

$this->title = 'Регистарция (Верстка singup.html)';
?>

<!-- Контент singup.html -->
<section class="registration__user">
    <h1>Регистрация аккаунта</h1>
    <div class="registration-wrapper">
        <!-- Форма -->
        <!-- <form class="registration__user-form form-create"> -->
        <?php 
        $form = ActiveForm::begin([
            'id' => 'signup-form',

            'errorCssClass' => 'input-danger',
            'validationStateOn' => 'input',

            'ajaxDataType' => 'json',
            'enableAjaxValidation' => true,
            'enableClientValidation' => false,
            'validateOnSubmit' => false,
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
            <!-- поле Электронная почта -->
            <!-- <label for="16">Электронная почта</label>
            <textarea class="input textarea" rows="1" id="16" name="" placeholder="kumarm@mail.ru"></textarea> -->
            <!-- <span>Введите валидный адрес электронной почты</span> -->
            <?php 
            echo $form->field($formModel, 'email')
                ->textarea([
                    'class' => 'input textarea',
                    'rows' => 1,
                    'placeholder' => 'kumarm@mail.ru',
                ]);
            ?>

            <!-- <label for="17">Ваше имя</label>
            <textarea class="input textarea" rows="1" id="17" name="" placeholder="Мамедов Кумар"></textarea>
            <span>Введите ваше имя и фамилию</span> -->
            <?php
            echo $form->field($formModel, 'full_name')
                ->textarea([
                    'class' => 'input textarea',
                    'rows' => 1,
                    'placeholder' => 'Райская Ева',
                ]);
            ?>

            <!-- <label for="18">Город проживания</label>
            <select id="18" class="multiple-select input town-select registration-town" size="1" name="town[]">
                <option value="Moscow">Москва</option>
                <option selected value="SPB">Санкт-Петербург</option>
                <option value="Krasnodar">Краснодар</option>
                <option value="Irkutsk">Иркутск</option>
                <option value="Bladivostok">Владивосток</option>
            </select>
            <span>Укажите город, чтобы находить подходящие задачи</span> -->
            <?php
            echo $form->field($formModel, 'location_id')
                ->dropDownList($formModel->getAttributeItems('location_id'), [
                    'prompt' => 'Выберите город',
                    'class' => 'multiple-select input town-select registration-town',
                    'size' => 1,
                ]);
            ?>
            
            <!-- <label class="input-danger" for="19">Пароль</label>
            <input class="input textarea " type="password" id="19" name="">
            <span>Длина пароля от 8 символов</span> -->
            <?php
            echo $form->field($formModel, 'password')
                ->passwordInput(['class' => 'input textarea']);
            ?>
            
            <!-- <button class="button button__registration" type="submit">Cоздать аккаунт</button> -->
            <?=Html::submitButton('Cоздать аккаунт', ['class' => 'button button__registration'])?>

        <?php ActiveForm::end()?>
        <!-- </form> -->
        <!-- /Форма -->
    </div>
</section>
<!-- /Контент singup.html -->

