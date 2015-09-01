<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\User */
/* @var $profile app\models\Profile */

$this->title = 'Регистрация магазина';

?>

<div class="reg-form">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?php $form = ActiveForm::begin([
        'enableAjaxValidation' => true,
    ]) ?>
    
    <?= $form->field($user, 'email')->input('email') ?>
    
    <?= $form->field($user, 'password')->passwordInput() ?>
    
    <?= $form->field($profile, 'url')->input('url') ?>
    
    <?= $form->field($profile, 'recommender_bonus') ?>
    
    <?= $form->field($profile, 'buyer_bonus') ?>
    
    <div id="hidden"></div>
    
    <div class='form-group'>
        <a href='#' id='add'>Добавить урл</a>
    </div>
    
    <div id="items" class='form-group'></div>
    
    <div class="form-group">
        <?= Html::submitButton('Дальше', ['class' => 'btn']) ?>
    </div>
    
    <?php ActiveForm::end(); ?>
    
    
</div>

<div style="display: none;">
    <div id='url-add' class='form-group item'>
        <?= Html::activeLabel($url, 'link') ?>
        <?= Html::activeTextInput($url, 'link', ['type'=>'url', 'required'=>'required']) ?>

        <?= Html::activeLabel($url, 'name') ?>
        <?= Html::activeTextInput($url, 'name') ?>

        <input type="button" class="delete" value="Удалить" />
    </div>
</div>

<?php

$script = <<<EOD
        
    $('#add').on('click', function(){

        element = $('#url-add').clone();

        element.removeAttr('id');
        $('#items').append(element);

        return false;
    });
    $('#items').on('click', '.delete', function() {
        if (confirm('Удалить?')) {
            $(this).closest('.item').remove();
        }
    });
    $('.btn').click(function(){
        prepareForm();
    });
    function prepareForm() {

        $('#hidden').empty();

        $('#items .item').each(function(index) {

            $('<input>').attr({
                type: 'hidden',
                name: 'Url['+index+'][link]',
                value: $(this).find('#url-link').val()
            }).appendTo('#hidden');

            $('<input>').attr({
                type: 'hidden',
                name: 'Url['+index+'][name]',
                value: $(this).find('#url-name').val()
            }).appendTo('#hidden');

        });

    };
EOD;

$this->registerJs($script);

?>