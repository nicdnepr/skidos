<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->email;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'email:email',
            'balance',
            'phone',
            'profile.recommender_bonus',
            'profile.buyer_bonus',
            'created_at:datetime',
            'profile.status.name'
        ],
    ]) ?>
    
    
    <?php if (\Yii::$app->authManager->checkAccess($model->id, User::ROLE_SHOP)): ?>
    
        <h3>
            Ссылки
        </h3>
    
        <?= GridView::widget([
            'dataProvider' => new yii\data\ActiveDataProvider(['query'=>$model->getUrls()]),
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'link:url',
                'name',

            ],
        ]); ?>
    
    <?php endif; ?>

</div>
