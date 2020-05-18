<?php 
use yii\helpers\html;
use yii\widgets\ActiveForm;
// use yii\widgets\ActiveField; // Не используем

$this->title = 'Исполнители (верстка Users.html)';
?>

<!-- Верстка Users.html контент -->

<section class="user__search">
    
    <!-- контент секция-сортировка: рейтинг, число заказов, популярность -->
    <div class="user__search-link">
        <p>Сортировать по:</p>
        <ul class="user__search-list">
            <li class="user__search-item user__search-item--current">
                <a href="#" class="link-regular">Рейтингу</a>
            </li>
            <li class="user__search-item">
                <a href="#" class="link-regular">Числу заказов</a>
            </li>
            <li class="user__search-item">
                <a href="#" class="link-regular">Популярности</a>
            </li>
        </ul>
    </div>

    <!-- begin single user -->
    <?php foreach($users as $user): ?>
    <div class="content-view__feedback-card user__search-wrapper">
        <div class="feedback-card__top">
            <div class="user__search-icon">
                <a href="#"><img src="./img/man-glasses.jpg" width="65" height="65"></a>
                <span><?= count($user->taskRunnings) ?> заданий</span>
                <span><?= count($user->ratedFeedbacks) ?> отзывов</span>
            </div>
            <div class="feedback-card__top--name user__search-card">
                <p class="link-name"><a href="#" class="link-regular"><?= $user->name ?></a></p>

                <!-- Рейтинг -->
                <?php $avg_point = $rating[$user->id_user]['avg_point'] ?? 0; ?>

                <!-- итерация желтой звездочки -->
                <?php for($i=1; $i <= $avg_point ; $i++): ?>
                <span></span>
                <?php endfor; ?>

                <!-- итерация белой звездочки -->
                <?php for($i=$avg_point; $i < 5; $i++): ?>
                <span class="star-disabled"></span>
                <?php endfor; ?>

                <!-- Оценка рейтинг -->
                <b><?= substr($avg_point, 0, 4); ?></b>

                <p class="user__search-content">
                    <?= $user->about ?>
                </p>
            </div>
            <span class="new-task__time">Был на сайте  <?= date('d.m.y',strtotime($user['reg_time'])) ?></span>
        </div>
        <div class="link-specialization user__search-link--bottom">
            <!-- итерация категории пользователя c использованием свойства-связи с viaTable -->
            <?php foreach($user->userCategories as $category): ?>
            <a href="#" class="link-regular"><?= $category->name ?></a>
            <?php endforeach; ?>

        </div>
    </div>
    <?php endforeach; ?>
    <!-- end single user -->

</section>

<section  class="search-task">
    <div class="search-task__wrapper">
        <!-- Форма начало -->
        <!-- <form class="search-task__form" name="users" method="post" action="#"> -->
        <?php 
            $form = ActiveForm::begin([
                'id' => 'users-form', 
                'options' => ['name' => 'users', 'class' => 'search-task__form'],
                'fieldConfig' => [
                    'template' => "{input}\n{label}", // Шаблон по умолчанию у большинства полей, также у каждого поля настраивается отдельно
                    'options' => ['tag' => false], // отключение создания дополнительных тегов <div> для любых полей созданных с помощью activeForm на уровне $form->field (но не действует на new activeField), отключение Bootstrap does not work https://forum.yiiframework.com/t/how-to-generate-form-without-div-class-form-group/75797/2
                ], 
                // 'validationStateOn' => ActiveForm::VALIDATION_STATE_ON_INPUT, // или VALIDATION_STATE_ON_CONTAINER
            ]) 
        ?>
            <fieldset class="search-task__categories">
                <legend>Категории</legend>

                <!-- ПОЛЕ категории. Тип список чекбоксов -->
                <!-- верстка -->
                <!-- <input class="visually-hidden checkbox__input" id="101" type="checkbox" name="" value="" checked disabled>
                <label for="101">Курьерские услуги </label>
                <input class="visually-hidden checkbox__input" id="102" type="checkbox" name="" value="" checked>
                <label  for="102">Грузоперевозки </label>
                <input class="visually-hidden checkbox__input" id="103" type="checkbox" name="" value="">
                <label  for="103">Переводы </label>
                <input class="visually-hidden checkbox__input" id="104" type="checkbox" name="" value="">
                <label  for="104">Строительство и ремонт </label>
                <input class="visually-hidden checkbox__input" id="105" type="checkbox" name="" value="">
                <label  for="105">Выгул животных </label> -->

                <?php /* ПОЛЕ Категории с помощью ActiveForm */
                    echo $form->field($usersForm, 'categories', [
                        'template' => "{input}", // убираем показ лейбла для всего контейнера списка чекбоксов
                    ])
                    ->checkboxList($usersForm->getAttributeItems('categories'), [
                        'tag' => false, // Отклчает создание общего контейнера div
                        'name' => 'categories[]', // для общего контейнера div и общий для всех чекбоксов
                        'unselect' => null, // Не создвать скрытое поле, скрытое поле отправляется для нулевого значения, если не выбран ни один чекбокс
                        'item' => function ($index, $label, $name, $checked, $value) {
                            $index += 101;

                            if ($index === 101 OR $index === 102) {
                                $checked = true;
                            }

                            return 
                                Html::checkbox($name, $checked, $options = [
                                    'id' => $index,
                                    'class' => 'visually-hidden checkbox__input',
                                    'value' => $value,
                                    // 'label' => $label, // В виде обертки не подходит
                                ]) . // !!!конкатенация
                                Html::label($label, $for = $index, $options = null);
                        }
                    ]); 
                ?>  

            </fieldset>
            <fieldset class="search-task__categories">
                <legend>Дополнительно</legend>
                
                <!-- Поле Сейчас свободен. Тип чекбокс -->
                <!-- верстка -->
                <!-- <input class="visually-hidden checkbox__input" id="106" type="checkbox" name="" value="" disabled>
                <label for="106">Сейчас свободен</label> -->
                <?php 
                    echo $form->field($usersForm, 'isAvailable')
                        ->checkbox([
                                'id' => 120,
                                'name' => 'isAvailable',
                                'class' => 'visually-hidden checkbox__input',
                                // 'value' => '', // Также создает атрибут !!!checked если пусто '', елси не указать по умолчанию =1 но атрибут checked не создается
                                'disabled' => true,
                                'uncheck' => null, // Не создвать скрытое поле, по умолчанию 0 - скрытое поле отправляется скрытое поле с именем isRemote и value=0
                            ],
                            $enclosedByLabel = false // или true - чекбокс внутри, false - label отдельный и не содержит чекбокс (но на практике остается текст, а теги label нет)
                        ) 
                        ->label($label = null, ['for' => '120', 'class' => null]) // Обязательно null вначале
                    ;
                ?>  

                <!-- Поле Сейчас онлайн. Тип чекбокс -->
                <!-- верстка -->
                <!-- <input class="visually-hidden checkbox__input" id="107" type="checkbox" name="" value="" checked>
                <label for="107">Сейчас онлайн</label> -->
                <?php 
                    echo $form->field($usersForm, 'isOnLine')
                        ->checkbox([
                                'id' => 121,
                                'name' => 'isOnLine',
                                'class' => 'visually-hidden checkbox__input',
                                // 'value' => '', // Также создает атрибут !!!checked если пусто '', елси не указать по умолчанию =1 но атрибут checked не создается
                                'checked' => true,
                                'uncheck' => null, // Не создвать скрытое поле, по умолчанию 0 - скрытое поле отправляется скрытое поле с именем isRemote и value=0
                            ],
                            $enclosedByLabel = false // или true - чекбокс внутри, false - label отдельный и не содержит чекбокс (но на практике остается текст, а теги label нет)
                        ) 
                        ->label($label = null, ['for' => '121', 'class' => null]) // Обязательно null вначале
                    ;
                ?>  

                <!-- Поле Есть отзывы. Тип чекбокс -->
                <!-- верстка -->
                <!-- <input class="visually-hidden checkbox__input" id="108" type="checkbox" name="" value="" checked>
                <label for="108">Есть отзывы</label> -->
                <?php 
                    echo $form->field($usersForm, 'isFeedbacks')
                        ->checkbox([
                                'id' => 122,
                                'name' => 'isFeedbacks',
                                'class' => 'visually-hidden checkbox__input',
                                // 'value' => '', // Также создает атрибут !!!checked если пусто '', елси не указать по умолчанию =1 но атрибут checked не создается
                                'checked' => true,
                                'uncheck' => null, // Не создвать скрытое поле, по умолчанию 0 - скрытое поле отправляется скрытое поле с именем isRemote и value=0
                            ],
                            $enclosedByLabel = false // или true - чекбокс внутри, false - label отдельный и не содержит чекбокс (но на практике остается текст, а теги label нет)
                        ) 
                        ->label($label = null, ['for' => '122', 'class' => null]) // Обязательно null вначале
                    ;
                ?>  

                <!-- Поле В избранном. Тип чекбокс -->
                <!-- верстка -->
                <!-- <input class="visually-hidden checkbox__input" id="109" type="checkbox" name="" value="" checked>
                <label for="109">В избранном</label> -->
                <?php 
                    echo $form->field($usersForm, 'isFavorite')
                        ->checkbox([
                                'id' => 123,
                                'name' => 'isFavorite',
                                'class' => 'visually-hidden checkbox__input',
                                // 'value' => '', // Также создает атрибут !!!checked если пусто '', елси не указать по умолчанию =1 но атрибут checked не создается
                                'checked' => true,
                                'uncheck' => null, // Не создвать скрытое поле, по умолчанию 0 - скрытое поле отправляется скрытое поле с именем isRemote и value=0
                            ],
                            $enclosedByLabel = false // или true - чекбокс внутри, false - label отдельный и не содержит чекбокс (но на практике остается текст, а теги label нет)
                        ) 
                        ->label($label = null, ['for' => '123', 'class' => null]) // Обязательно null вначале
                    ;
                ?>  

            </fieldset>

            <!-- ПОЛЕ Поиск по имени. тип search-->
            <!-- Верстка -->
            <!-- <label class="search-task__name" for="110">Поиск по имени</label>
            <input class="input-middle input" id="110" type="search" name="q" placeholder=""> -->
            <?php 
                echo $form->field($usersForm, 'search', [
                        'template' => "{label}\n{input}", // отличается от общего в форме, Обязательно !!!двойные кавычки, тк выводится на печать \n, символ переноса строки поддерживает только "\n"
                    ]) 
                    ->label($label = null, ['for' => '130','class' => 'search-task__name'])
                    ->input('search', ['id' => '130', 'name' => 'q', 'class' => 'input-middle input', 'placeholder' => ''])
                ;
            ?>

            <button class="button" type="submit">Искать</button>
        
        <?php ActiveForm::end() ?>
        <!-- Форма окончание -->
        <!-- </form> -->
    </div>
</section>
