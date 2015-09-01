<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\bootstrap\ActiveForm;
use app\models\Url;
use app\models\User;

class RegistrationController extends Controller
{
    /**
     * регистрация магазина
     * @return type
     */
    public function actionShop()
    {
        $request = Yii::$app->request;
        
        $model = new \app\models\registration\ShopForm();
        $profile = new \app\models\Profile;
        
        if ($request->isAjax && $model->load($request->post()) && $profile->load($request->post())) {
            
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            
            $errors = \yii\helpers\ArrayHelper::merge(ActiveForm::validate($model), ActiveForm::validate($profile));
            
            return $errors;
        }
        
        if ( $model->load($request->post()) && $profile->load($request->post()) && \yii\base\Model::validateMultiple([$model, $profile]) ) {
            
            $model->save($profile, $request->post('Url'));
            Yii::$app->session->setFlash('success', 'Регистрация успешна');
            return $this->goHome();
            
        }
        
        return $this->render('shop', [
            'user' => $model,
            'profile' => $profile,
            'url' => new Url,
        ]);
    }
    
    
    /**
     * регистрация юзера по имейл
     * если регистрация для покупки, то передаются параметры рекомендатель и урл
     * @param type $affiliate_id
     * @param type $url_id
     * @return type
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionUser($affiliate_id = null, $url_id = null)
    {
        $request = Yii::$app->request;
        
        $model = new \app\models\registration\UserForm;
        
        if ($request->isAjax && $model->load($request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        if ($model->load($request->post()) && $model->validate()) {
            
            $user = User::findByUsername($model->email);
            
            if (!$user) {
                $user = $model->save();
            }
            
            $user->setCookie();
            
            if ($affiliate_id === null || $url_id === null) {
                Yii::$app->session->setFlash('success', 'Регистрация успешна. Пароль выслан на почту');
                return $this->goHome();
            }
            
            $url = Url::findOne($url_id);
            
            if (!$url) {
                throw new \yii\web\NotFoundHttpException('Урл не найден');
            }
            
            $user->purchase($affiliate_id, $url);
            
            return $this->redirect($url->link);
        }
        
        return $this->render('user', [
            'model' => $model
        ]);
    }
}
