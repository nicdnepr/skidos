<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Url */

$this->title = 'Редактирование';
$this->params['breadcrumbs'][] = $model->name;
?>
<div class="url-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
