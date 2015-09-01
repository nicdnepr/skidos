<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Url;
use app\models\User;
use app\models\PasswordResetRequestForm;

class SiteController extends Controller
{
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * главная страница
     * @return type
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            
            if (\Yii::$app->user->can(User::ROLE_ADMIN)) {
                return $this->goHome();
            } 
            
            Yii::$app->user->identity->setCookie();
            return $this->redirect(['user/profile']);
            
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
    
    
    /**
     * запрос на новый пароль
     * @return type
     */
    public function actionResetPassword()
    {
        $model = new PasswordResetRequestForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                
                Yii::$app->getSession()->setFlash('success', 'Инструкции высланы на почту');
                return $this->goHome();
                
            } else {
                Yii::$app->getSession()->setFlash('error', 'Ошибка отправки');
            }
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * установить новый пароль
     * @param type $token
     * @return type
     */
    public function actionNewPassword($token)
    { 
        
        $user = User::findByPasswordResetToken($token);
        
        if ($user) {
            
            $user->setNewPassword();
            Yii::$app->getSession()->setFlash('success', 'Новый пароль выслан на почту');
            
        } else {
            Yii::$app->getSession()->setFlash('error', 'Токен не действителен');
        }

        return $this->goHome();

    }
    
    public function actionCookie()
    {
        return Yii::$app->request->cookies->getValue('user_id');
    }
}
