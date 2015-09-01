<?php
namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string@ $email
 * @property string@ $balance
 * @property string $phone
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $auth_key
 * @property string $rating
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password
 */
class User extends ActiveRecord implements IdentityInterface
{
    
    const ROLE_ADMIN = 'admin';
    const ROLE_SHOP = 'shop';
    const ROLE_USER = 'user';
    
    public $password;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['phone', 'string', 'min' => 11, 'max' => 12]
        ];
    }
    
    public function attributeLabels()
    {
        $p = new Profile;
        
        return [
            'id' => 'ID',
            'created_at' => 'Дата регистрации',
            'balance' => 'Баланс',
            'phone' => 'Телефон',
            'email' => 'Email',
            'url' => $p->getAttributeLabel('url'),
            'recommender_bonus' => $p->getAttributeLabel('recommender_bonus'),
            'buyer_bonus' => $p->getAttributeLabel('buyer_bonus')
        ];
    }
    
    /*
    public function scenarios()
    {
        $scenarios = [
            'shop_reg' => ['email', 'password'],
            'user_reg' => ['email'],
            'user_buy' => ['username'], // при покупке юзер указывает имейл
            'update' => ['email', 'password', 'phone']
        ];
        
        return \yii\helpers\ArrayHelper::merge(parent::scenarios(), $scenarios);
    }*/

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('метод не поддерживается');
    }

    /**
     * Finds user by email or phone
     *
     * @param  string      $value
     * @return static|null
     */
    public static function findByUsername($value)
    {
        //$field = 'phone';
        
        //if ($value == 'admin' || strpos($value, '@') > 0) {
        //    $field = 'email';
        //}
        
        return static::findOne(['email' => $value]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + Yii::$app->params['tokenExpire'] > time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
    
    public function generatePassword()
    {
        return substr(Yii::$app->security->generateRandomString(), 0, 8);
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id'=>'id']);
    }
    
    public function getUrls()
    {
        return $this->hasMany(Url::className(), ['user_id'=>'id']);
    }
    
    public function getPays()
    {
        return $this->hasMany(Paylog::className(), ['user_id'=>'id']);
    }
    
    public function getShopComments()
    {
        return $this->hasMany(Comment::className(), ['user_id'=>'id']);
    }
    
    public function getAuthorComments()
    {
        return $this->hasMany(Comment::className(), ['author_id'=>'id']);
    }
    
    /**
     * список покупок пользователя
     * @return type
     */
    public function getUserPurchases()
    {
        return $this->hasMany(Purchase::className(), ['user_id'=>'id']);
    }
    
    public function getShopPurchases()
    {
        return $this->hasMany(Purchase::className(), ['shop_id'=>'id']);
    }
    
    public function getAffiliatePurchases()
    {
        return $this->hasMany(Purchase::className(), ['affiliate_id'=>'id']);
    }
    
    /**
     * переход по реф ссылке
     * @param type $affiliate_id
     * @param type $url
     */
    public function purchase($affiliate_id, $url)
    {
        if (static::findOne($affiliate_id) === null) {
            $affiliate_id = null;
        }
        
        $purchase = new Purchase;
        
        if ($this->id != $affiliate_id) {
            $purchase->affiliate_id = $affiliate_id;
        }
        
        $purchase->user_id = $this->id;
        $purchase->url_id = $url->id;
        $purchase->shop_id = $url->user_id;
        $purchase->status = Paylog::STATUS_PENDING;
        $purchase->save(false);
        
        Yii::$app->mailer->compose('user/purchase', ['user'=>$this, 'url'=>$url])
            ->setFrom(Yii::$app->params['emailFrom'])
            ->setTo($url->user->email)
            ->setSubject('Переход по ссылке')
            ->send();
    }
    
    public function setNewPassword()
    {
        $this->password = $this->generatePassword();
        $this->setPassword($this->password);
        $this->removePasswordResetToken();
        $this->save(false);
        
        Yii::$app->mailer->compose('user/password', ['model'=>$this])
            ->setFrom(Yii::$app->params['emailFrom'])
            ->setTo($this->email)
            ->setSubject('Новый пароль')
            ->send();
    }
    
    /**
     * вернуть юзеров по заданной роли
     * @param type $role
     * @return type
     */
    public static function findByRole($role)
    {
        return static::find()
            ->join('LEFT JOIN', 'auth_assignment', 'auth_assignment.user_id = user.id')
            ->where(['auth_assignment.item_name' => $role]);
    }
    
    /**
     * установка куки для процесса покупки без авторизации
     */
    public function setCookie()
    {
        $cookie = new \yii\web\Cookie([
            'name' => 'user_id',
            'value' => $this->id,
            'expire' => time() + Yii::$app->params['cookieExpire']
        ]);
        Yii::$app->response->cookies->add($cookie);
    }
    
    
    /**
     * выплата бонуса юзеру на телефон или внутренний счет
     * @param type $sum
     * @param type $shop_id
     */
    public function pay($sum, $shop_id)
    {
        if (!$this->payMobile($sum)) {
            $this->balance += $sum;
            $this->save(false);
        }

        $paylog = new Paylog;
        $paylog->user_id = $this->id;
        $paylog->sum = $sum;
        $paylog->status = Paylog::STATUS_SUCCESS;
        $paylog->type = Paylog::PAY_OUT;
        $paylog->save(false);
        
        $paylog = new Paylog;
        $paylog->user_id = $shop_id;
        $paylog->sum = $sum;
        $paylog->status = Paylog::STATUS_SUCCESS;
        $paylog->type = Paylog::PAY_OUT;
        $paylog->save(false);
        
    }
    
    /**
     * выплата на мобильный счет
     * @param type $sum
     * @return boolean
     */
    private function payMobile($sum)
    {
        $ym = new \YandexMoney\API(Yii::$app->params['ym']['access_token']);
        
        $result = $ym->requestPayment([
            'pattern_id' => 'phone-topup',
            'phone-number' => $this->phone,
            'amount' => $sum
        ]);
        
        if ($result->status == 'success') {
            
            $result = $ym->processPayment([
                'request_id' => $result->request_id
            ]);
            
            return $result->status == 'success';
            
        }
        
        return false;
    }
}
