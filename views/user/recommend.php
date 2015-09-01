<?php

use Yii;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
$this->title = 'Порекомендовать';
?>

<div class="recommend-form">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>
        Список email\телефонов для рассылки
    </p>
    
    <?php \yii\widgets\Pjax::begin() ?>
    
    <?= \app\widgets\Alert::widget() ?>
    
    <?= Html::beginForm('', 'post', [
        'data-pjax'=>1,
    ]) ?>

    <?= Html::textarea('data', '', ['rows'=>10, 'cols'=>50]) ?>

    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php Html::endForm() ?>
    
    <?php \yii\widgets\Pjax::end() ?>

</div>