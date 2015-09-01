<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Purchase */

$this->title = 'Оплата покупки ' . \Yii::$app->formatter->asDatetime($model->created_at);

$this->params['breadcrumbs'][] = 'Покупки пользователей';
?>
<div class="purchase-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
