<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ShopStatus */

$this->title = 'Статус';
$this->params['breadcrumbs'][] = ['label' => 'Shop Statuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-status-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
