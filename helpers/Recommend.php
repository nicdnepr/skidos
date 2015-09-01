<?php

namespace app\helpers;

use Yii;

class Recommend
{
    /**
     * рассылка приглашений на почту и телефоны
     * @param type $data
     */
    public static function send($data)
    {
        $message = 'Получи скидку тут ' . \yii\helpers\Url::home(true);
        
        $data = array_unique(array_map('trim', explode("\n", $data)));
            
        foreach ($data as $item) {

            try {
                
                if (strpos($item, '@') > 0) {
                    
                    Yii::$app->mailer->compose('recommend', ['message'=>$message])
                        ->setFrom(Yii::$app->params['emailFrom'])
                        ->setTo($item)
                        ->setSubject('Рекомендация')
                        ->send();

                } else {

                    $phone = str_replace('+', '', $item);
                    
                    if ($phone[0] == 7 && is_numeric($phone) && strlen($phone) > 10) {
                        Yii::$app->sms->send_sms($phone, $message);
                    }
                }
                
            } catch (Exception $ex) {

            }
        }
    }
}