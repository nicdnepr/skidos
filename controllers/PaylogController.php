<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\Paylog;
use app\models\PaylogSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * PaylogController implements the CRUD actions for Paylog model.
 */
class PaylogController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['list'],
                        'allow' => true,
                        'roles' => [User::ROLE_SHOP, User::ROLE_USER]
                    ],
//                    [
//                        'actions' => ['create'],
//                        'allow' => true,
//                        'roles' => [User::ROLE_SHOP]
//                    ],
                    [
                        'allow' => true,
                        'roles' => [User::ROLE_ADMIN]
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Paylog models.
     * @return mixed
     */
    public function actionIndex()
    {
        
        $searchModel = new PaylogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionList()
    {
        
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => Yii::$app->user->identity->getPays(),
            'sort' => [
                'defaultOrder' => ['created_at'=>SORT_DESC]
            ]
        ]);
        
        return $this->render('list', [
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single Paylog model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Paylog model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Paylog([
            'scenario' => 'admin'
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            
            $user = User::findOne(['email'=>$model->email]);
            
            $model->type = Paylog::PAY_IN;
            $model->status = Paylog::STATUS_PENDING;
            $model->user_id = $user->id;
            
            if ($model->save()) {
                
                //$merchant = Yii::$app->robokassa;
                $model->addBalance();
                Yii::$app->session->setFlash('success', 'Баланс пополнен');
                return $this->redirect($this->goHome());
                //return $merchant->payment($model->sum, $model->id, 'Пополнение счета', null, Yii::$app->user->identity->email);
                //return $this->redirect(['view', 'id' => $model->id]);
            }
            
        }
        
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Paylog model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Paylog model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Paylog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Paylog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Paylog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
