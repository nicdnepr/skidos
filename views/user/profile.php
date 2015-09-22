<?php

use yii\widgets\DetailView;
use yii\widgets\Pjax;
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Профиль';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php Pjax::begin() ?>

    <?= Html::beginForm('', 'post', ['data-pjax' => '', 'class' => 'form-inline']); ?>
        Введите ссылку <?= Html::input('url', 'link', null, ['class' => 'form-control', 'required' => 'required']) ?>
        <?= Html::submitButton('Получить', ['class' => 'btn btn-primary']) ?>
    <?= Html::endForm() ?>

    <?= $result ?>

<?php Pjax::end() ?>


<div class="user-view">

    <h1>Профиль</h1>
    
    <?= DetailView::widget([
        'model' => Yii::$app->user->identity,
        'attributes' => $attributes
    ]) ?>
    
</div>