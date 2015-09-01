<?php

/* @var $this yii\web\View */
/* @var $user app\models\User */

$resetLink = \yii\helpers\Url::to(['site/new-password', 'token' => $user->password_reset_token], true);
?>

<div>
    
    <h1>Запрос нового пароля</h1>
    
    Чтобы получить новый пароль перейдите по ссылке <?= \Yii::$app->formatter->asUrl($resetLink) ?>
    
</div>