<?php

namespace frontend\models\db;

use Yii;

/**
 * This is the model class for table "user_portfolio_images".
 *
 * @property int $image_id
 * @property int $user_id
 * @property string $title
 * @property string|null $image_addr
 *
 * @property Users $user
 */
class UserPortfolioImages extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'user_portfolio_images';
    }

    public function rules()
    {
        return [
            [['user_id', 'title'], 'required'],
            [['user_id'], 'integer'],
            [['title', 'image_addr'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'image_id' => 'Image ID',
            'user_id' => 'User ID',
            'title' => 'Title',
            'image_addr' => 'Image Addr',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(Users::class, ['user_id' => 'user_id']);
    }
}
