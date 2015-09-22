<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "url".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $link
 * @property string $name
 *
 * @property User $user
 */
class Url extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'url';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['link'], 'required'],
            [['link', 'name'], 'string', 'max' => 255],
            [['link'], 'url']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User',
            'link' => 'Ссылка',
            'name' => 'Название',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    public function behaviors()
    {
        return [
            \yii\behaviors\TimestampBehavior::className(),
        ];
    }
    
    public function addUrl($link)
    {
        $shop_id = null;
        $text = '';
        
        $profile = new Profile;
        $host = $profile->getHost($link);
        /* @var $shop Profile */
        $shop = Profile::find()->where(['host'=>$host])->one();
        
        
        if ($shop) {
            
            $shop_id = $shop->user_id;
            $text .= 'магазин ' . $shop->url . '<br>';
            $text .= 'бонус покупателю ' . $shop->buyer_bonus . '<br>';
            $text .= 'бонус рекомендателю ' . $shop->recommender_bonus . '<br>';
            $text .= 'статус ' . $shop->status->name . '<br>';
            
        } elseif (!ModerateShop::find()->where(['url'=>$host])->exists()) {
            $moderateShop = new ModerateShop([
                'user_id' => Yii::$app->user->identity->id,
                'url' => $host
            ]);
            $moderateShop->save();
            
        }
        
        $text .= 'Вы можете давать эту ссылку своим друзьям<br>';
        
        $url = new Url([
            'user_id' => Yii::$app->user->identity->id,
            'link' => $link,
            'shop_id' => $shop_id
        ]);
        $url->save();
        
        return $text . \yii\helpers\Url::to(['purchase/create', 'affiliate_id'=>Yii::$app->user->identity->id, 'url_id'=>$url->id], true);
    }
}
