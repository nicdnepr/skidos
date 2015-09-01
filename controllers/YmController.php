<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class YmController extends Controller
{
    public function actionToken()
    {
        $redirect_url = \yii\helpers\Url::to(['token'], true);
        
        if (isset($_GET['code'])) {
            
            $token = \YandexMoney\API::getAccessToken(Yii::$app->params['ym']['client_id'], $_GET['code'], $redirect_url, Yii::$app->params['ym']['client_secret']);
            
            if (isset($token->access_token)) {
                mail('webdvlp@yandex.ru', 'token', $token->access_token);
                echo 'token generated';
            } else {
                echo $token->error;
            }
            
            Yii::$app->end();
            
        }
        
        return $this->redirect(\YandexMoney\API::buildObtainTokenUrl(Yii::$app->params['ym']['client_id'], $redirect_url, ['payment-shop']));
    }
    
    public function actionPay()
    {
        $error = '';
        
        $ym = new \YandexMoney\API(Yii::$app->params['ym']['access_token']);
        
        $result = $ym->requestPayment([
            'pattern_id' => 'phone-topup',
            'phone-number' => '79787540970',
            'amount' => '1'
        ]);
        
        if ($result->status == 'success') {
            
            var_dump($result);
//            $result = $ym->processPayment([
//                'request_id' => $result->request_id
//            ]);
            
            if ($result->status == 'success') {
                
            } elseif ($result->status == 'refused') {
            
                $error = $result->error;

                if (!empty($result->error_description)) {
                    $error .= '<br>' . $result->error_description;
                }
            }
            
        } elseif ($result->status == 'refused') {
            
            $error = $result->error;
            
            if (!empty($result->error_description)) {
                $error .= '<br>' . $result->error_description;
            }
        }
        
        if (!empty($error)) {
            throw new \yii\web\BadRequestHttpException($error);
        }
        
    }
    
}