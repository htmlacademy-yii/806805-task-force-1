<?php 

namespace frontend\models;

use yii\db\ActiveRecord;

class Tasks extends ActiveRecord 
{
    // Наследуемый метод переопределение, позволяет менять название модели от имени table of schema, в нашем случае метод не требуется - как пример
    public static function tableName()
    {
        return "tasks"; // table name с которой будет работать модель
    }
}