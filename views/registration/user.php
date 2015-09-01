<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Регистрация пользователя';
?>

<div class="reg-form">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?php $form = ActiveForm::begin([
        'enableAjaxValidation' => true,
    ]) ?>
    
    <?= $form->field($model, 'email')->input('email') ?>
    
    <?= $form->field($model, 'phone') ?>
    
    <div class="form-group">
        <?= Html::submitButton('Дальше', ['class' => 'btn']) ?>
    </div>
    
    <?php ActiveForm::end(); ?>
    
</div>
