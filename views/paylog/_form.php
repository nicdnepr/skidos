<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Paylog */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="paylog-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'email')->input('email') ?>

    <?= $form->field($model, 'sum') ?>

    <div class="form-group">
        <?= Html::submitButton('Дальше', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
