<?php

namespace app\controllers;


use yii\web\NotFoundHttpException;
use app\models\Paylog;
use app\models\User;
use yii\web\Controller;

class RobocassaController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'result' => [
                'class' => '\robokassa\ResultAction',
                'callback' => [$this, 'resultCallback'],
            ],
            'success' => [
                'class' => '\robokassa\SuccessAction',
                'callback' => [$this, 'successCallback'],
            ],
            'fail' => [
                'class' => '\robokassa\FailAction',
                'callback' => [$this, 'failCallback'],
            ],
        ];
    }
    
    /**
     * Callback.
     * @param \robokassa\Merchant $merchant merchant.
     * @param integer $nInvId invoice ID.
     * @param float $nOutSum sum.
     * @param array $shp user attributes.
     */
    public function successCallback($merchant, $nInvId, $nOutSum, $shp)
    {
        /* @var $transaction \yii\db\Transaction */
        $transaction = \Yii::$app->db->beginTransaction();
        
        try {
            
            $paylog = $this->findPay($nInvId);
            $user = User::findOne($paylog->user_id);
            
            $user->balance += $nOutSum;
            $paylog->updateAttributes(['status' => Paylog::STATUS_SUCCESS]);
            $transaction->commit();
            
        } catch (\Exception $ex) {
            $transaction->rollBack();
        }
        
        return $this->goBack();
    }
    public function resultCallback($merchant, $nInvId, $nOutSum, $shp)
    {
        return 'OK' . $nInvId;
    }
    public function failCallback($merchant, $nInvId, $nOutSum, $shp)
    {
        $this->findPay($nInvId)->updateAttributes(['status' => Paylog::STATUS_FAIL]);
        return 'Отказ от оплаты';
    }
    
    protected function findPay($id)
    {
        if (($model = Paylog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException;
        }
    }
}