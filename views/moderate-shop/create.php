<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ModerateShop */

$this->title = 'Create Moderate Shop';
$this->params['breadcrumbs'][] = ['label' => 'Moderate Shops', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="moderate-shop-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
