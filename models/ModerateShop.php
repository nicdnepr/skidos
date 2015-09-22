<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "moderate_shop".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $url
 * @property string $created_at
 */
class ModerateShop extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'moderate_shop';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['url'], 'string', 'max' => 255],
            [['url'], 'unique'],
        ];
    }
    
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id'=>'user_id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'url' => 'Url',
            'created_at' => 'Дата создания',
        ];
    }
    
    public function behaviors()
    {
        return [
            'TimestampBehavior' => [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'updatedAtAttribute' => false,
                'value' => new \yii\db\Expression('NOW()')
            ]
        ];
    }
}
