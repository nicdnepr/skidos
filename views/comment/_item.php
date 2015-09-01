<?php
use yii\helpers\Html;
?>

<div class="comment-view">
    
    <?php 
    
        $link = '';
        
        if (Yii::$app->user->can(\app\models\User::ROLE_SHOP)) {
            
            
            $link = ' ' . Html::a('Ответить', ['update', 'id'=>$model->id]);
            
            if (is_null($model->answer)) {
                
            }
        }
    ?>
    
    <?= \yii\widgets\DetailView::widget([
        'model' => $model,
        'attributes' => [
            'message:ntext',
            
            [
                'attribute' => 'answer',
                'format' => 'raw',
                'visible' => !is_null($model->answer),
                'value' => nl2br(yii\helpers\Html::encode($model->answer))
            ],
            
            'created_at:datetime',
            
            [
                'label' => 'Операция',
                'format' => 'raw',
                'visible' => !empty($link),
                'value' => $link
            ]
        ]
    ]) ?>
    
    
</div>
