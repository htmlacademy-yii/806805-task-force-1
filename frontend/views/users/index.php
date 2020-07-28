<?php
use yii\helpers\html;
use yii\helpers\Url;
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
            <?php foreach ($sortings as $sorting): ?>
            <li class="user__search-item <?=Yii::$app->request->get('sorting') === $sorting['label'] ? 'user__search-item--current' : ''?>">
                <a href="<?=Url::to(['', 'sorting' => $sorting['label']])?>" class="link-regular"><?=$sorting['title']?></a>
            </li>
            <?php endforeach;?>
        </ul>
    </div>

    <!-- begin single user -->
    <?php foreach ($users as $user): ?>
    <div class="content-view__feedback-card user__search-wrapper">
        <div class="feedback-card__top">
            <div class="user__search-icon">
                <a href="<?=Url::to(['users/view', 'ID' => $user->user_id])?>"><img src="/<?=$user->avatar_addr ?: \Yii::$app->params['defaultAvatarAddr']?>" width="65" height="65"></a>
                <span><?=$user->tasks_count?> заданий</span>
                <span><?=$user->feedbacks_count?> отзывов</span>
            </div>
            <div class="feedback-card__top--name user__search-card">
                <p class="link-name"><a href="<?=Url::to(['users/view', 'ID' => $user->user_id])?>" class="link-regular"><?=$user->full_name?> (ID: <?=$user->user_id?>)</a></p>
                <!-- Рейтинг -->
                <?php $avg_point = $user->avg_point ?? 0;?>

                <!-- итерация желтой звездочки -->
                <?php for ($i = 1; $i <= $avg_point; $i++): ?>
                    <span></span>
                <?php endfor;?>

                <!-- итерация белой звездочки -->
                <?php for ($i = $avg_point; $i < 5; $i++): ?>
                    <span class="star-disabled"></span>
                <?php endfor;?>

                <!-- Средний балл рейтинг -->
                <b><?=Yii::$app->formatter->asDecimal($avg_point, 2)?></b>
                <p class="user__search-content">
                    <?=$user->desc_text?>
                </p>
            </div>
            <span class="new-task__time">Был на сайте<br><?=Yii::$app->formatter->asRelativeTime(strtotime($user->activity_time))?></span>
        </div>
        <div class="link-specialization user__search-link--bottom">
            <!-- итерация категории пользователя связи -->
            <?php foreach ($user->userSpecializations as $category): ?>
            <a href="#" class="link-regular"><?=$category->title?></a>
            <?php endforeach;?>

        </div>
    </div>
    <?php endforeach;?>
    <!-- end single user -->

    <!-- Верстка pagination -->

    <?php if (count($users) >= 5): ?>
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
    <?php endif;?>

</section>

<section  class="search-task">
    <div class="search-task__wrapper">
        <!-- Форма начало -->
        <!-- <form class="search-task__form" name="users" method="post" action="#"> -->
        <?php
$form = ActiveForm::begin([
    'id' => 'id-users-form',
    'options' => [
        'name' => 'test-2', // Имя формы не используется в ActiveForm, получение тела запроса производится по имени таблицы в модели в зависимости от модели в полях, которые автоматически назначаются при указании модели
        'class' => 'search-task__form',
    ],
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
        // 'name' => 'categories[]', // для общего контейнера div и общий для всех чекбоксов // Задается автоматически согласно метода load(), если задать вручную, то не попадает в load() в массив с именем формы как в модели
        // 'unselect' => null, // null - Не создвать скрытое поле, по умолчанию 0 - скрытое поле отправляется с именем value=0. Здесь ставим null, чтобы показать все задания во всех категориях. Хотя результат с 0 совпадает, зато меньше запросов в БД. Причем это нужно для корректной работы andFilterWhere, в то время как andWhere удобнее использовать скрытую отправку 0, тк не придется делать проверку на существование.
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

                <!-- Поле Сейчас свободен. Тип чекбокс, по умолчанию нет -->
                <!-- верстка -->
                <!-- <input class="visually-hidden checkbox__input" id="106" type="checkbox" name="" value="" disabled>
                <label for="106">Сейчас свободен</label> -->
                <?php
echo $form->field($usersForm, 'isAvailable')
    ->checkbox([
        'id' => 201,
        // 'name' => 'isAvailable',
        'class' => 'visually-hidden checkbox__input',
        // 'value' => '', // Также создает атрибут !!!checked если пусто '', елси не указать по умолчанию =1 но атрибут checked не создается
        // 'disabled' => true,
        'uncheck' => null, // Не создвать скрытое поле, по умолчанию 0 - скрытое поле отправляется скрытое поле с именем isRemote и value=0
    ],
        $enclosedByLabel = false// или true - чекбокс внутри, false - label отдельный и не содержит чекбокс
    )
// ->label($label = null, ['for' => '201', 'class' => null]) // Обязательно null вначале
;
?>

                <!-- Поле Сейчас онлайн. Тип чекбокс -->
                <!-- верстка -->
                <!-- <input class="visually-hidden checkbox__input" id="107" type="checkbox" name="" value="" checked>
                <label for="107">Сейчас онлайн</label> -->
                <?php
echo $form->field($usersForm, 'isOnLine')
    ->checkbox([
        'id' => 202,
        // 'name' => 'isOnLine', // Задается автоматически согласно метода load(), если задать вручную, то не попадает в load() в массив с именем формы как в модели
        'class' => 'visually-hidden checkbox__input',
        // 'value' => 1, // создается автоматически, по умолчанию всегда =1 !!!если задать значение, то влияет на сортировку но текущее состояние не отображается // Также создает атрибут !!!checked если пусто ''
        // 'checked' => true, // отправляется, влияет на сортировку но не отображается, как будто остается всегда включенным
        'uncheck' => null, // null - Не создвать скрытое поле, по умолчанию 0 - скрытое поле отправляется с именем value=0. Это позволяет использовать в поиске 0 как критерий поиска. Например если поле не отправлять то выборка включает как 0 так и 1. Если отправить 0 то выборка вернет только 0 из БД. Здесь ставим null, чтобы по умолчанию показывать все а не только с 0 (только в офисе)
    ],
        $enclosedByLabel = false// или true - чекбокс внутри, false - label отдельный и не содержит чекбокс
    )
// ->label($label = null, ['for' => '202', 'class' => null]) // Обязательно null вначале
;
?>

                <!-- Поле Есть отзывы. Тип чекбокс -->
                <!-- верстка -->
                <!-- <input class="visually-hidden checkbox__input" id="108" type="checkbox" name="" value="" checked>
                <label for="108">Есть отзывы</label> -->
                <?php
echo $form->field($usersForm, 'isFeedbacks')
    ->checkbox([
        'id' => 203,
        // 'name' => 'isFeedbacks', // Задается автоматически согласно метода load(), если задать вручную, то не попадает в load() в массив с именем формы как в модели
        'class' => 'visually-hidden checkbox__input',
        // 'value' => '', // создается автоматически, по умолчанию всегда =1 !!!если задать значение, то влияет на сортировку но текущее состояние не отображается // Также создает атрибут !!!checked если пусто ''
        // 'checked' => true, // отправляется, влияет на сортировку но не отображается, как будто остается всегда включенным
        'uncheck' => null, // Не создвать скрытое поле, по умолчанию 0 - скрытое поле отправляется скрытое поле с именем isRemote и value=0
    ],
        $enclosedByLabel = false// или true - чекбокс внутри, false - label отдельный и не содержит чекбокс
    )
// ->label($label = null, ['for' => '203', 'class' => null]) // Обязательно null вначале
;
?>

                <!-- Поле В избранном. Тип чекбокс -->
                <!-- верстка -->
                <!-- <input class="visually-hidden checkbox__input" id="109" type="checkbox" name="" value="" checked>
                <label for="109">В избранном</label> -->
                <?php
echo $form->field($usersForm, 'isFavorite')
    ->checkbox([
        'id' => 204,
        // 'name' => 'isFavorite', // Задается автоматически согласно метода load(), если задать вручную, то не попадает в load() в массив с именем формы как в модели
        'class' => 'visually-hidden checkbox__input',
        // 'value' => '', // создается автоматически, по умолчанию всегда =1 !!!если задать значение, то влияет на сортировку но текущее состояние не отображается // Также создает атрибут !!!checked если пусто ''
        // 'checked' => true, // отправляется, влияет на сортировку но не отображается, как будто остается всегда включенным
        'uncheck' => null, // Не создвать скрытое поле, по умолчанию 0 - скрытое поле отправляется скрытое поле с именем isRemote и value=0
    ],
        $enclosedByLabel = false// или true - чекбокс внутри, false - label отдельный и не содержит чекбокс
    )
// ->label($label = null, ['for' => '204', 'class' => null]) // Обязательно null вначале
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
    ->label($label = null, ['for' => '205', 'class' => 'search-task__name'])
    ->input('search', [
        'id' => '205',
        // 'name' => 'q', // Задается автоматически согласно метода load(), если задать вручную, то не попадает в load() в массив с именем формы как в модели
        'class' => 'input-middle input',
        'placeholder' => '',
    ])
;
?>

            <!-- <button class="button" type="submit">Искать</button> -->
            <?=Html::submitButton('Искать', ['class' => 'button'])?>

        <?php ActiveForm::end()?>
        <!-- Форма окончание -->
        <!-- </form> -->
    </div>
</section>
