<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Список магазинов';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="shop-list">
    
    <h1>Магазины</h1>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'url',
                'format' => 'raw',
                'value' => function ($model) {
                    return \Yii::$app->formatter->asUrl($model->profile->url);
                }
            ],
            [
                'attribute' => 'buyer_bonus',
                'value' => 'profile.buyer_bonus'
            ],
            [
                'attribute' => 'recommender_bonus',
                'value' => 'profile.recommender_bonus'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('подробнее', ['display', 'id'=>$model->id]);
                    }
                ]
            ]
        ]
    ])?>
</div>