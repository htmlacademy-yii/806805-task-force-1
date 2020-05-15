<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;

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
                            <a href="#" class="link-regular"><h2><?= ucfirst($task->name) ?></h2></a>
                            <a class="new-task__type link-regular" href="#"><p><?= $task->category->name ?></p></a>
                        </div>
                        <div class="new-task__icon new-task__icon--<?= $task->category->symbol ?>"></div>
                            <p class="new-task_description">
                                <?= $task->description ?>
                            </p>
                            <b class="new-task__price new-task__price--<?= $task->category->symbol ?>"><?= $task->price ?><b> ₽</b></b>
                            <p class="new-task__place"><?= $task['address'] ?></p>
                            <span class="new-task__time"><?= date( 'd.m.y',strtotime($task['add_time'])) ?></span>
                    </div>
                    <?php endforeach; ?>
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
                    <?php $form = ActiveForm::begin([
                        'id' => 'task-form', 
                        'options' => ['name' => 'test', 'class' => 'search-task__form'],
                        'fieldConfig' => [
                            'template' => "{label}\n{input}", // пример
                            'options' => ['tag' => false], // отключение создания дополнительных тегов <div> для любых полей созданных с помощью activeForm (но не действует на new activeField), отключение Bootstrap does not work https://forum.yiiframework.com/t/how-to-generate-form-without-div-class-form-group/75797/2
                        ], 
                        'validationStateOn' => ActiveForm::VALIDATION_STATE_ON_INPUT, // или VALIDATION_STATE_ON_CONTAINER
                        ]) ?>
                    <!-- <form class="search-task__form" name="test" method="post" action="#"> -->
                        <?php
                        // Создаем все переменные как $ключ=значени c !!!префиксом form
                        // extract($taskForm->attributeLabels(), EXTR_PREFIX_ALL, 'form');
                        ?>

                        <fieldset class="search-task__categories">
                            <legend>Категории</legend>

                            <!-- ПОЛЕ Категории тип чекбокс-список-->
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


                            
                        </fieldset>

                        <fieldset class="search-task__categories">
                            <legend>Дополнительно</legend>
                            
                            <!-- ПОЛЕ Без откликов тип чекбокс-->
                            <!-- <input class="visually-hidden checkbox__input" id="6" type="checkbox" name="" value="">
                            <label for="6">Без откликов</label>-->
                            <?php 
                            echo $form->field($taskForm, 'isOffers', [
                                    'template' => "{input}\n{label}", // Обязательно !!!двойные кавычки, тк выводится на печать \n, символ переноса строки поддерживает только "\n"
                                ])
                                ->checkBox([
                                        // 'labelOptions' => ['for' => '7', 'class' => null], // Вообще не работает внутри ->checkBox()
                                        // 'inputOption' => ['class' => null], // Вообще не работает внутри ->checkBox()
                                        'id' => 6,
                                        'name' => 'isOffers',
                                        'class' => 'visually-hidden checkbox__input',
                                        // 'value' => '', // Также создает атрибут !!!checked если пусто '', елси не указать по умолчанию =1 но атрибут checked не создается
                                        'uncheck' => null, // Не создвать скрытое поле, по умолчанию 0 - скрытое поле отправляется скрытое поле с именем isRemote и value=0
                                        // 'label' => null, // По умолчанию, Не защищен. Если $enclosedByLabel = false то если ввести тектс !!!обычный текст, сам тег пропадает 
                                    ],
                                    $enclosedByLabel = false) // или true - чекбокс внутри, false - label отдельный и не содержит чекбокс (но на практике остается текст, а теги label нет)
                                ->label(null, ['for' => '6', 'class' => null]) // Обязательно null вначале
                            ;
                        ?>  

                            <!-- ПОЛЕ Удаленная работа тип чекбокс-->
                            <!-- <input class="visually-hidden checkbox__input" id="7" type="checkbox" name="" value="" checked>
                            <label for="7">Удаленная работа </label> -->
                        <?php 
                            echo $form->field($taskForm, 'isRemote', [
                                    'template' => "{input}\n{label}", // Обязательно !!!двойные кавычки, тк выводится на печать \n, символ переноса строки поддерживает только "\n"
                                ])
                                ->checkBox([
                                        // 'labelOptions' => ['for' => '7', 'class' => null], // Вообще не работает внутри ->checkBox()
                                        // 'inputOption' => ['class' => null], // Вообще не работает внутри ->checkBox()
                                        'id' => 7,
                                        'name' => 'isRemote',
                                        'class' => 'visually-hidden checkbox__input',
                                        // 'value' => '', // Также создает атрибут !!!checked если пусто '', елси не указать по умолчанию =1 но атрибут checked не создается
                                        'checked' => true, // передать значение на сервер по умолчанию, но в yii можно с помощью value = '' создается checked
                                        'uncheck' => null, // Не создвать скрытое поле, по умолчанию 0 - скрытое поле отправляется скрытое поле с именем isRemote и value=0
                                        // 'label' => null, // По умолчанию, Не защищен. Если $enclosedByLabel = false то если ввести тектс !!!обычный текст, сам тег пропадает 
                                    ],
                                    $enclosedByLabel = false) // или true - чекбокс внутри, false - label отдельный и не содержит чекбокс (но на практике остается текст, а теги label нет)
                                ->label(null, ['for' => '7', 'class' => null]) // Обязательно null вначале
                            ;
                        ?>  
                        </fieldset>

                        <!-- ПОЛЕ Период тип выпадающий список селектор-->
                        <!-- <label class="search-task__name" for="8">Период</label>
                           <select class="multiple-select input" id="8"size="1" name="time[]">
                            <option value="day">За день</option>
                            <option selected value="week">За неделю</option>
                            <option value="month">За месяц</option>
                        </select> -->

                        <?php
                            echo $form->field($taskForm, 'dateInterval', [
                                    'labelOptions' => ['for' => '8', 'class' => 'search-task__name'],
                                    'inputOptions' => ['id' => '8', 'name' => 'time', 'class' => 'multiple-select input', 'size' => 1,],
                                ])
                                ->dropdownList($taskForm->getAttributeItems('dateInterval'), [
                                    'options' => ['week' => ['selected' => true]]
                                ])
                            ;
                        ?>

                        <!-- ПОЛЕ Поиск по названию тип текст-->
                        <!-- <label class="search-task__name" for="9">Поиск по названию</label>
                            <input class="input-middle ipunt" id="9" type="search" name="q" placeholder=""> -->

                        <?php 
                            // Вариант-1 создание поля type text с помощью только ActiveForm
                            echo $form->field($taskForm, 'search') 
                                ->label(null, ['for' => '9','class' => 'search-task__name'])
                                ->input('search', ['id' => '9', 'name' => 'q', 'class' => 'input-middle ipunt', 'placeholder' => ''])
                            ;
                        ?>
                        <?php 
                            // Вариант-2 создание поля type text с помощью ActiveField
                            // $field = (new ActiveField([
                            //         'form' => $form,
                            //         'model' => $taskForm, 
                            //         'attribute' => 'search',
                            //     ]))
                            //     ->input('search', ['name' => 'q', 'id' => '9', 'class' => 'input-middle ipunt', 'placeholder' => ''])
                            //     ->label(null, ['for' => '9','class' => 'search-task__name'])
                            //     ->render()
                            // ;
                            // echo $field;
                        ?>
                        <button class="button" type="submit">Искать</button>
                    <?php ActiveForm::end() ?>
                    <!-- </form> -->
                </div>
            </section>


