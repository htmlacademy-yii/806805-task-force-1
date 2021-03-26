<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Задания (Верстка browse.html)';
?>

<!-- Верстка browse.html контент -->
<section class="new-task">
    <div class="new-task__wrapper">
        <h1>Новые задания</h1>
        <!-- single task -->
        <?php foreach ($tasks as $task): ?>
        <div class="new-task__card">
            <div class="new-task__title">
                <a href="<?=Url::to(['view', 'ID' => $task->task_id])?>" class="link-regular"><h2><?=ucfirst($task->title)?></h2></a>
                <a class="new-task__type link-regular" href="<?=Url::to(['index', 'category' => $task->category_id])?>"><p><?=$task->category->title?></p></a>
            </div>
            <div class="new-task__icon new-task__icon--<?=$task->category->label?>"></div>
                <p class="new-task_description">
                    <?=$task->desc_text?>
                </p>
                <b class="new-task__price new-task__price--<?=$task->category->label?>"><?=$task->price?><b> ₽</b></b>
                <p class="new-task__place"><?=$task['full_address']?></p>
                <span class="new-task__time"><?=Yii::$app->formatter->asRelativeTime(strtotime($task->add_time))?></span>
        </div>
        <?php endforeach;?>
        <!-- /single task -->
    </div>
    <!-- Верстка pagination -->
    <div class="new-task__pagination">
        <ul class="new-task__pagination-list">
            <li class="pagination__item"><a href="#"></a></li>
            <li class="pagination__item pagination__item--current">
                <a>1</a></li>
            <li class="pagination__item"><a href="#">2</a></li>
            <li class="pagination__item"><a href="#">3</a></li>
            <li class="pagination__item"><a href="#"></a></li>
        </ul>
    </div>
    <!-- /Верстка pagination -->
</section>
<!-- Верстка right panel -->
<section  class="search-task">
    <div class="search-task__wrapper">
        <!-- Форма начало -->
        <?php
        $form = ActiveForm::begin([
            'id' => 'tasks-form',
            'action' => '/tasks', 
            'options' => ['class' => 'search-task__form'],
            'fieldConfig' => [
                'template' => "{input}\n{label}", 
                'options' => ['tag' => false]
            ],
        ])
        ?>
            <fieldset class="search-task__categories">
                <legend>Категории</legend>
                <!-- ПОЛЕ Категории тип чекбокс-список-->
                <?php 
                echo $form->field($tasksForm, 'categories', ['template' => "{input}"])
                    ->checkboxList(
                        $tasksForm->getAttributeItems('categories'), 
                        [
                            'tag' => false, 
                            'item' => function ($index, $label, $name, $checked, $value) {
                                ++$index;

                                $field = Html::checkbox($name, $checked, $options = [
                                    'id' => $index,
                                    'class' => 'visually-hidden checkbox__input',
                                    'value' => $value, 
                                ]);
                                $field .= Html::label($label, $for = $index, $options = null);

                                return $field;
                            }
                        ]
                    );
                ?>
            </fieldset>
            <fieldset class="search-task__categories">
                <legend>Дополнительно</legend>
                <!-- ПОЛЕ Без откликов. тип чекбокс, по умолчанию нет -->
                <?php
                echo $form->field($tasksForm, 'isOffers')
                    ->checkbox(
                        ['class' => 'visually-hidden checkbox__input', 'uncheck' => null],
                        $enclosedByLabel = false 
                    );
                ?>
                <!-- ПОЛЕ Удаленная работа. тип чекбокс выбран -->
                <?php
                echo $form->field($tasksForm, 'isRemote')
                    ->checkBox(
                        ['class' => 'visually-hidden checkbox__input', 'uncheck' => null],
                        $enclosedByLabel = false
                    );
                ?>
            </fieldset>
            <!-- ПОЛЕ Период. тип выпадающий список|селектор-->
            <?php
            echo $form->field($tasksForm, 'dateInterval', [
                    'template' => "{label}\n{input}", 
                    'labelOptions' => ['class' => 'search-task__name'],
                    'inputOptions' => ['class' => 'multiple-select input', 'size' => 1],
                ])
                ->dropdownList(
                    $tasksForm->getAttributeItems('dateInterval'), 
                    ['prompt' => 'За всё время']
                );
            ?>
            <!-- ПОЛЕ Поиск по названию. тип search-->
            <?php
            echo $form->field(
                    $tasksForm, 
                    'search', 
                    ['template' => "{label}\n{input}"]
                )
                ->label($label = null, ['class' => 'search-task__name'])
                ->input('search', ['class' => 'input-middle input', 'placeholder' => '']);
            ?>

            <?=Html::submitButton('Искать', ['class' => 'button'])?>
        <?php ActiveForm::end()?>
        <!-- Форма окончание -->
    </div>
</section>


