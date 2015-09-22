<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Магазины на модерации';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="moderate-shop-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'user.email:email',
            'user.phone',
            'url:url',
            'created_at',

            
        ],
    ]); ?>

</div>
