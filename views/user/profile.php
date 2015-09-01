<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */

$this->title = 'Профиль';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="user-view">

    <h1>Профиль</h1>
    
    <?= DetailView::widget([
        'model' => Yii::$app->user->identity,
        'attributes' => $attributes
    ]) ?>
    
    
    

</div>