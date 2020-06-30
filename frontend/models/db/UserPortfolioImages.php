<?php

namespace frontend\models\db;

use Yii;

/**
 * This is the model class for table "user_portfolio_images".
 *
 * @property int $image_id
 * @property int $user_id
 * @property string|null $image_addr
 *
 * @property Users $user
 */
class UserPortfolioImages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_portfolio_images';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['image_addr'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'image_id' => 'Image ID',
            'user_id' => 'User ID',
            'image_addr' => 'Image Addr',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['user_id' => 'user_id']);
    }
}
