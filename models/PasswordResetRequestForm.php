<?php

namespace app\models;

use yii\base\Model;
/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'trim',],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist', 'targetClass' => '\app\models\User',],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findByUsername($this->email);

        if ($user) {
            if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
                $user->generatePasswordResetToken();
            }

            if ($user->save()) {
                return \Yii::$app->mailer->compose('user/passwordResetToken', ['user' => $user])
                    ->setFrom([\Yii::$app->params['emailFrom'] => \Yii::$app->name . ' robot'])
                    ->setTo($this->email)
                    ->setSubject('Восстановление пароля ' . \Yii::$app->name)
                    ->send();
            }
        }

        return false;
    }
}
