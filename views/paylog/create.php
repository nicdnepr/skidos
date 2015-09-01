<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Paylog */

$this->title = 'Пополнить счет';
$this->params['breadcrumbs'][] = ['label'=>'Профиль', 'url'=>['user/profile']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="paylog-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
