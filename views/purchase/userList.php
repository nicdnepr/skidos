<?php

use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Покупки';
$this->params['breadcrumbs'][] = $this->title;

?>


<div class="purchase-list">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'sum',
            [
                'attribute' => 'status',
                'value' => function($model) {
                    $p = new \app\models\Paylog;
                    return $p->getStatus($model->status);
                }
            ],
            [
                'attribute' => 'url_id',
                'format' => 'raw',
                'value' => function($model) {
                    return \Yii::$app->formatter->asUrl($model->url->link);
                }
            ],
            'created_at:datetime'
        ],
    ]); ?>
    
</div>