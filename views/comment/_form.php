<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Comment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="comment-form">

    <?php $form = ActiveForm::begin([
        'action' => isset($action) ? $action : null,
    ]); ?>
    
    <?php 
    
    if (\Yii::$app->user->can(\app\models\User::ROLE_ADMIN)) {
        echo $form->field($model, 'message')->textarea(['rows' => 6]);
    } elseif (\Yii::$app->user->can(\app\models\User::ROLE_SHOP)) {
        echo Yii::$app->formatter->asNtext($model->message);
    }
    
    ?>
    
    <?= $form->field($model, 'answer')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
