<?php

use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Покупки пользователей';
$this->params['breadcrumbs'][] = $this->title;

?>


<div class="purchase-list">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'phone',
                'value' => 'user.phone'
            ],
            [
                'attribute' => 'email',
                'format' => 'raw',
                'value' => function ($model) {
                    return \Yii::$app->formatter->asEmail($model->user->email);
                }
            ],
            'sum',
            [
                'attribute' => 'status',
                'value' => function($model) {
                    $p = new \app\models\Paylog;
                    return $p->getStatus($model->status);
                }
            ],
            'created_at:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        if ($model->status == \app\models\Paylog::STATUS_PENDING) {
                            $options = [
                                'title' => 'Подтвердить',
                                'aria-label' => 'Подтвердить',
                                'data-pjax' => '0',
                            ];
                            return Html::a('<span class="glyphicon glyphicon-ok"></span>', $url, $options);
                        }
                    },
                    'delete' => function ($url, $model) {
                        if ($model->status == \app\models\Paylog::STATUS_PENDING) {
                            $options = [
                                'title' => Yii::t('yii', 'Delete'),
                                'aria-label' => Yii::t('yii', 'Delete'),
                                'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                'data-method' => 'post',
                                'data-pjax' => '0',
                            ];
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, $options);
                        }
                    }
                ]
            ],
        ],
    ]); ?>
    
</div>