<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "purchase".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $affiliate_id
 * @property integer $shop_id
 * @property integer $url_id
 * @property string $sum
 * @property integer $status
 * @property integer $created_at
 *
 * @property Url $url
 * @property User $shop
 * @property User $user
 */
class Purchase extends \yii\db\ActiveRecord
{
    
    private $buyer_bonus;
    private $recommender_bonus;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'purchase';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['sum', 'required'],
            ['sum', 'number', 'min'=>1],
            ['sum', 'checkBalance']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $u = new User;
        return [
            'id' => 'ID',
            'user_id' => 'User',
            'shop_id' => 'Магазин',
            'url_id' => 'Ссылка',
            'sum' => 'Сумма',
            'status' => 'Статус',
            'created_at' => 'Дата покупки',
            'phone' => $u->getAttributeLabel('phone')
        ];
    }
    
    /**
     * проверка баланса для выплаты
     * @param type $attribute
     * @param type $params
     */
    public function checkBalance($attribute, $params)
    {
        /* @var $profile Profile */
        $profile = Yii::$app->user->identity->profile;

        $this->buyer_bonus = $this->$attribute * $profile->buyer_bonus / 100;
        $this->recommender_bonus = $this->$attribute * $profile->recommender_bonus / 100;

        if ( Yii::$app->user->identity->balance < ($this->buyer_bonus + $this->recommender_bonus) ) {
            $this->addError($attribute, 'На балансе недостаточно денег для оплаты');
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUrl()
    {
        return $this->hasOne(Url::className(), ['id' => 'url_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShop()
    {
        return $this->hasOne(User::className(), ['id' => 'shop_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return PurchaseQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PurchaseQuery(get_called_class());
    }
    
    public function behaviors()
    {
        return [
            \yii\behaviors\TimestampBehavior::className()
        ];
    }
    
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            
            if (!$insert) {
                
            }
            
            
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * обработка баланса магазина, установка выплат юзерам
     * @throws \Exception
     */
    public function proceedBalance()
    {
        /* @var $transaction \yii\db\Transaction */
        $transaction = Yii::$app->db->beginTransaction();

        try {

            Yii::$app->user->identity->balance -= $this->buyer_bonus + $this->recommender_bonus;
            Yii::$app->user->identity->save(false);

            /* @var $buyer User */
            $buyer = User::findOne($this->user_id);
            $buyer->pay($this->buyer_bonus, $this->shop_id);
            
            if ($this->affiliate_id !== null) {
                
                /* @var $recommender User */
                $recommender = User::findOne($this->affiliate_id);
                $recommender->pay($this->recommender_bonus, $this->shop_id);

            }

            $transaction->commit();

        } catch (Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }
    }
}
