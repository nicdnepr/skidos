<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pay_log".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $sum
 * @property integer $type
 * @property integer $status
 * @property integer $created_at
 *
 * @property User $user
 */
class Paylog extends \yii\db\ActiveRecord
{
    // $type
    const PAY_IN = 1; //пополнение счета
    const PAY_OUT = 2; //выплата
    
    // $status
    const STATUS_PENDING = 1;
    const STATUS_FAIL = 2;
    const STATUS_SUCCESS = 3;
    const STATUS_CANCEL = 4;
    const STATUS_APPROVE = 5;
    const STATUS_PAID = 6;
    
    private $typeList;
    private $statusList;
    
    public $email;
    
    public function init()
    {
        parent::init();
        
        $this->typeList = [
            self::PAY_IN => 'Пополнение счета',
            self::PAY_OUT => 'Выплата'
        ];
        
        $this->statusList = [
            self::STATUS_PENDING => 'На рассмотрении магазина',
            self::STATUS_FAIL => 'Не оплачен',
            self::STATUS_SUCCESS => 'Оплачен'
        ];
        
        $this->status = self::STATUS_SUCCESS;
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pay_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sum'], 'required'],
            [['user_id', 'type'], 'integer'],
            [['sum'], 'number'],
            ['email', 'required', 'on'=>'admin'],
            ['email', 'email', 'on'=>'admin'],
            ['email', 'exist', 'targetClass'=>'\app\models\User', 'on'=>'admin'],
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
            'sum' => 'Сумма',
            'type' => 'Операция',
            'status' => 'Статус',
            'created_at' => 'Дата',
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
            \yii\behaviors\TimestampBehavior::className()
        ];
    }
    
    public function getType($value)
    {
        return $this->typeList[$value];
    }
    
    public function getStatus($value)
    {
        return $this->statusList[$value];
    }
    
    public function beforeSave($insert)
    {
        
        if (parent::beforeSave($insert)) {
            
        
            return true;
            
        } else {
            return false;
        }
        
    }
    
    public function addBalance()
    {
        $this->status = self::STATUS_SUCCESS;
        $this->save(false);
        
        $user = User::findOne(['email'=>$this->email]);
        $user->balance += $this->sum;
        $user->save(false);
    }
    
}
