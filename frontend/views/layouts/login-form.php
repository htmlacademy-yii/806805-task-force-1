<?php 
use yii\helpers\html;
use yii\widgets\ActiveForm;

?>

<!-- Форма входа. Модальное окно -->
<section class="modal enter-form form-modal" id="enter-form">
    <h2>Вход на сайт</h2>
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',

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
            'class' => false,
        ],
        'fieldConfig' => [
            'template' => "{label}\n{input}",
            'options' => ['tag' => false],
        ],
    ]);?>
        <p>
            <?php 
            echo $form->field($formModel, 'email')
                ->label(null, ['class' => 'form-modal-description'])
                ->input(
                    'email', 
                    [
                        'id' => 'enter-email', 
                        'class' => 'enter-form-email input input-middle'
                    ]
                );
            ?>
        </p>
        <p>
            <?php 
            echo $form->field($formModel, 'password', ['template' => "{label}\n{input}\n{error}"])
                ->label(null, ['class' => 'form-modal-description'])
                ->input(
                    'password', 
                    [
                        'id' => 'enter-password', 
                        'class' => 'enter-form-email input input-middle'
                    ]
                )->error(['tag' => 'span', 'style' => 'margin-bottom: 20px;']);
            ?>
        </p>
        <?=Html::submitButton('Войти', ['class' => 'button'])?>
    <?php ActiveForm::end();?>
    <button class="form-modal-close" type="button">Закрыть</button>
</section>
<!-- /Форма входа. Модальное окно -->