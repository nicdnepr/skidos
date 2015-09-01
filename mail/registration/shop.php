<?php

use yii\widgets\DetailView;

?>

<div>
    
    <h1>Успешная регистрация</h1>
    
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'email',
            'password',
            'profile.url',
            'profile.recommender_bonus',
            'profile.buyer_bonus',
        ],
    ]) ?>
    
</div>