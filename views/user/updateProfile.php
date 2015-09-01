<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\User;
use app\models\Profile;

/* @var $this yii\web\View */
/* @var $user User */
/* @var $profile Profile */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Редактирование профиля';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="profile-form">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($user, 'phone') ?>

    <?php if (Yii::$app->user->can(User::ROLE_SHOP)): ?>
    
        <?= $form->field($profile, 'buyer_bonus') ?>

        <?= $form->field($profile, 'recommender_bonus') ?>
    
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton('Дальше', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
