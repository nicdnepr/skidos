<?php

use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Ссылки';
//$this->params['breadcrumbs'][] = ['label'=>'Профиль', 'url'=>['user/profile']];
$this->params['breadcrumbs'][] = $this->title;

?>


<div class="url-list">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $filterModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'link',
                'format' => 'raw',
                'value' => function($model) {
                    $link = \yii\helpers\Url::to(['purchase/create', 'affiliate_id'=>\Yii::$app->user->identity->id, 'url_id'=>$model->id], true);
                    return Html::a(Html::encode($model->link), $link);
                }
            ],
            'name',
        ],
    ]); ?>
    
</div>