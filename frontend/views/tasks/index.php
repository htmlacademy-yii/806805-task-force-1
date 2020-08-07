<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
// use yii\widgets\ActiveField; // Не используем

$this->title = 'Задания (Верстка browse.html)';
?>

<!-- Верстка browse.html контент -->

<section class="new-task">

    <div class="new-task__wrapper">
        <h1>Новые задания</h1>

        <!-- begin single task -->
        <?php foreach ($tasks as $task): ?>
        <div class="new-task__card">
            <div class="new-task__title">
                <a href="<?=Url::to(['tasks/view', 'ID' => $task->task_id])?>" class="link-regular"><h2><?=ucfirst($task->title)?></h2></a>
                <a class="new-task__type link-regular" href="<?=Url::to(['tasks/index', 'category' => $task->category_id])?>"><p><?=$task->category->title?></p></a>
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
        <!-- end single task -->

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

</section>

<!-- Верстка right panel -->
<section  class="search-task">
    <div class="search-task__wrapper">
        <!-- Форма начало -->
        <!-- <form class="search-task__form" name="test" method="post" action="#"> -->
        <?php
        $form = ActiveForm::begin([
            'id' => 'tasks-form',
            'action' => '/tasks', // указываем, чтобы при параметре <category> в строке запроса при отправке формы перенаправлять на страницу без <category>  
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
                <!-- Верстка -->
                <!-- <input class="visually-hidden checkbox__input" id="1" type="checkbox" name="" value="" checked>
                <label for="1">Курьерские услуги </label>
                <input class="visually-hidden checkbox__input" id="2" type="checkbox" name="" value="" checked>
                <label  for="2">Грузоперевозки </label>
                <input class="visually-hidden checkbox__input" id="3" type="checkbox" name="" value="">
                <label  for="3">Переводы </label>
                <input class="visually-hidden checkbox__input" id="4" type="checkbox" name="" value="">
                <label  for="4">Строительство и ремонт </label>
                <input class="visually-hidden checkbox__input" id="5" type="checkbox" name="" value="">
                <label  for="5">Выгул животных </label> -->
                <?php /* ПОЛЕ Категории с помощью ActiveForm */
                echo $form->field($tasksForm, 'categories', ['template' => "{input}"])
                    ->checkboxList($tasksForm->getAttributeItems('categories'), [
                        'tag' => false, // Отклчает создание общего контейнера div
                        'item' => function ($index, $label, $name, $checked, $value) {
                            ++$index;

                            return
                            Html::checkbox($name, $checked, $options = [
                                'id' => $index,
                                'class' => 'visually-hidden checkbox__input',
                                'value' => $value, // !!!отличается от других, если не задавать то создается автоматически у всех всегда =1. !!!В данном случае необходимо использовать $value, для сохранения сортировки, так как значения хранятся в модели.
                                // 'label' => $label, // В виде обертки не подходит
                            ])
                            . // Конкатенация
                            Html::label($label, $for = $index, $options = null);
                        },
                    ]);
                ?>
            </fieldset>
            <fieldset class="search-task__categories">
                <legend>Дополнительно</legend>
                <!-- ПОЛЕ Без откликов. тип чекбокс, по умолчанию нет -->
                <!-- <input class="visually-hidden checkbox__input" id="6" type="checkbox" name="" value="">
                <label for="6">Без откликов</label>-->
                <?php
                echo $form->field($tasksForm, 'isOffers')
                    ->checkbox(
                        ['class' => 'visually-hidden checkbox__input', 'uncheck' => null],
                        $enclosedByLabel = false 
                    );
                ?>
                <!-- ПОЛЕ Удаленная работа. тип чекбокс выбран -->
                <!-- <input class="visually-hidden checkbox__input" id="7" type="checkbox" name="" value="" checked>
                <label for="7">Удаленная работа </label> -->
                <?php
                echo $form->field($tasksForm, 'isRemote')
                    ->checkBox(
                        ['class' => 'visually-hidden checkbox__input', 'uncheck' => null],
                        $enclosedByLabel = false
                    );
                ?>
            </fieldset>

            <!-- ПОЛЕ Период. тип выпадающий список|селектор-->
            <!-- Верстка -->
            <!-- <label class="search-task__name" for="8">Период</label>
                <select class="multiple-select input" id="8"size="1" name="time[]">
                <option value="day">За день</option>
                <option selected value="week">За неделю</option>
                <option value="month">За месяц</option>
            </select> -->
            <?php
            echo $form->field($tasksForm, 'dateInterval', [
                    'template' => "{label}\n{input}", 
                    'labelOptions' => ['class' => 'search-task__name'],
                    'inputOptions' => ['class' => 'multiple-select input', 'size' => 1],
                ])
                ->dropdownList($tasksForm->getAttributeItems('dateInterval'), [
                    'prompt' => 'За всё время',
                ]);
            ?>
            <!-- ПОЛЕ Поиск по названию. тип search-->
            <!-- Верстка -->
            <!-- <label class="search-task__name" for="9">Поиск по названию</label>
                <input class="input-middle input" id="9" type="search" name="q" placeholder=""> -->
            <?php
            echo $form->field($tasksForm, 'search', [
                    'template' => "{label}\n{input}", // отличается от общего в форме, Обязательно !!!двойные кавычки, тк выводится на печать \n, символ переноса строки поддерживает только "\n"
                ])
                ->label($label = null, ['class' => 'search-task__name'])
                ->input('search', ['class' => 'input-middle input', 'placeholder' => '']);
            ?>

            <!-- <button class="button" type="submit">Искать</button> -->
            <?=Html::submitButton('Искать', ['class' => 'button'])?>

        <?php ActiveForm::end()?>
        <!-- Форма окончание -->
        <!-- </form> -->
    </div>
</section>


