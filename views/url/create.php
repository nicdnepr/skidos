<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Url */

$this->title = 'Добавить урл';
$this->params['breadcrumbs'][] = ['label'=>'Профиль', 'url'=>['user/profile']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="url-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
