<?php

//use Yii;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Транзакции';
$this->params['breadcrumbs'][] = $this->title;

?>


<div class="purchase-list">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'shop_id',
                'value' => function($model) {
                    return $model->shop ? $model->shop->profile->host : 'Не найден';
                }
            ],
            'url:url',
            [
                'header' => 'почему я получил',
                'value' => function($model) {
                    return 'я ' . ($model->user_id == Yii::$app->user->identity->id ? 'покупатель' : 'рекомендатель');
                }
            ],
            [
                'header' => 'кто купил',
                //'format' => 'email',
                'value' => function($model) {
                    return $model->user_id == Yii::$app->user->identity->id ? 'я' : $model->user->email;
                }
            ],
            
            [
                'attribute' => 'status',
                'value' => function($model) {
                    //$p = new \app\models\Paylog;
                    return (new \app\models\Paylog)->getStatus($model->status);
                }
            ],
            'created_at:datetime',
            'sum',
            [
                'header' => 'бонус',
                'value' => function($model) {
                    if (!$model->shop)
                        return 0;
                    
                    return $model->sum * ($model->user_id == Yii::$app->user->identity->id ? $model->shop->profile->buyer_bonus : $model->shop->profile->recommender_bonus);
                }
            ],
            [
                'header' => '',
                'format' => 'raw',
                'value' => function($model) {
                    return \yii\helpers\Html::a('Пожаловаться', '#', ['class'=>'complaint', 'data'=>$model->id]);
                }
            ]
        ],
    ]); ?>
    
    <p>
        Полученная сумма <?= Yii::$app->user->identity->getFullSum() ?>
    </p>
    
</div>

<?php 
    $this->registerJs("
        $('.complaint').on('click', function(){
            $.post('complaint', {id: $(this).attr('data')});
            $(this).parent().text('Отослано');
        });
    ");
?>