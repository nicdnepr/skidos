<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "profile".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $url
 * @property string $host
 * @property integer $recommender_bonus
 * @property integer $buyer_bonus
 * @property integer $status_id
 */
class Profile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url', 'recommender_bonus', 'buyer_bonus'], 'required'],
            [['recommender_bonus', 'buyer_bonus'], 'integer', 'min'=>1, 'max'=>100],
            [['url'], 'string', 'max' => 255],
            ['url', 'url']
        ];
    }
    
    public function getStatus()
    {
        return $this->hasOne(ShopStatus::className(), ['id'=>'status_id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'url' => 'Url главной страницы',
            'recommender_bonus' => 'Бонус для рекомендателя %',
            'buyer_bonus' => 'Бонус для покупателя %',
        ];
    }
    
    public function getHost($url)
    {
        $list = parse_url($url);
        return str_replace('www.', '', $list['host']);
    }
}
