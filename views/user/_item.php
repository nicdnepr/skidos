<div class="shop-view">
    <?= \yii\widgets\DetailView::widget([
        'model' => $model,
        'attributes' => [
            'profile.url:url',
            'profile.buyer_bonus',
            'profile.recommender_bonus',
            [
                'attribute' => 'id',
                'label' => '',
                'format' => 'raw',
                'value' => \yii\helpers\Html::a('Детали', ['user/display', 'id'=>$model->id])
            ]
        ]
    ]) ?>
</div>

