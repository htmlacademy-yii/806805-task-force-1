<?php

namespace frontend\models\db;

use Yii;

/**
 * This is the model class for table "categories".
 *
 * @property int $id
 * @property string $symbol
 * @property string $name
 *
 * @property Tasks[] $tasks
 * @property UserSpecializations[] $userSpecializations
 */
class Categories extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['symbol', 'name'], 'required'],
            [['symbol', 'name'], 'string', 'max' => 32],
            [['symbol'], 'unique'],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'symbol' => Yii::t('app', 'Symbol'),
            'name' => Yii::t('app', 'Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Tasks::className(), ['category_id' => 'id'])->inverseOf('category');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserSpecializations()
    {
        return $this->hasMany(UserSpecializations::className(), ['category_id' => 'id'])->inverseOf('category');
    }
}
