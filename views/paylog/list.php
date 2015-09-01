<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $model app\models\Paylog */

$this->title = 'Платежи';
//$this->params['breadcrumbs'][] = ['label'=>'Профиль', 'url'=>['user/profile']];
$this->params['breadcrumbs'][] = $this->title;

?>


<div class="pays-list">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'sum',
            [
                'attribute' => 'type',
                'value' => function ($model) {
                    return $model->getType($model->type);
                }
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->getStatus($model->status);
                }
            ],
            'created_at:datetime',
        ],
    ]); ?>
    
</div>