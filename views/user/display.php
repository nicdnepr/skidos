<?php

use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\ListView;
use yii\helpers\Html;

$this->title = 'Информация';
//$this->params['breadcrumbs'][] = ['label'=>'Профиль', 'url'=>['user/profile']];
$this->params['breadcrumbs'][] = $this->title;


app\assets\AddThisAsset::register($this);
?>


<div class="user-view">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'email:email',
            'profile.buyer_bonus',
            'profile.recommender_bonus',
        ],
    ]) ?>
    
    <h1>Ссылки</h1>
    
    <?= GridView::widget([
        'dataProvider' => $urlDataProvider,
        'filterModel' => $urlFilterModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            
            [
                'attribute' => 'name',
                'header' => 'Прямая ссылка на товар',
                'format' => 'raw',
                'value' => function($model) {
                    
                    $name = empty($model->name) ? 'Ссылка на товар' : $model->name;
                    return Html::a(Html::encode($name), $model->link);
                }
            ],
                    
            [
                //'attribute' => 'link',
                'header' => 'Рекомендательная ссылка',
                'format' => 'raw',
                'value' => function($model) {
                    
                    $link = \yii\helpers\Url::to(['purchase/create', 'affiliate_id'=>\Yii::$app->user->identity->id, 'url_id'=>$model->id], true);
                    //return Html::a(Html::encode($model->link), $link) . '<div class="addthis_sharing_toolbox" data-url="' . $link . '" data-title="Покупай тут"></div>';
                    return Html::textInput('', $link, ['style' => 'width:100%']) . '<div class="addthis_sharing_toolbox" data-url="' . $link . '" data-title="Покупай тут"></div>';
                }
            ],
        ],
    ]); ?>
    
    <h1>Комментарии</h1>
    
    <?= ListView::widget([
        'dataProvider' => $commentDataProvider,
        'itemView' => '/comment/_item'
    ]); ?>
    
    <h1>Добавить комментарий</h1>
    
    <?= $this->render('/comment/_form', [
        'model' => $comment,
        'action' => yii\helpers\Url::to(['comment/create', 'shop_id'=>$comment->user_id])
    ]) ?>
    
</div>

<?php
$script = <<<EOD
    $("input[type='text']").on("click", function () {
        $(this).select();
    });
EOD;
$this->registerJs($script);
?>