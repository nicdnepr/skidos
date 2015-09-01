<?php

namespace app\controllers;

use Yii;
use app\models\Url;
use app\models\UrlSearch;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\filters\AccessControl;

/**
 * UrlController implements the CRUD actions for Url model.
 */
class UrlController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['own-list', 'create', 'update', 'delete', 'view'],
                        'allow' => true,
                        'roles' => [User::ROLE_SHOP]
                    ],
                    /*[
                        'actions' => ['list'],
                        'allow' => true,
                        'roles' => [User::ROLE_USER]
                    ],*/
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
     * Lists all Url models.
     * @return mixed
     */
    public function actionIndex()
    {
        Yii::$app->user->setReturnUrl(\yii\helpers\Url::to());
        
        $filterModel = new UrlSearch;
        $dataProvider = $filterModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'filterModel' => $filterModel
        ]);
    }
    
    /**
     * отобразить ссылки текущего магазина
     * @return type
     */
    public function actionOwnList()//
    {
        Yii::$app->user->setReturnUrl(\yii\helpers\Url::to());
        
        $dataProvider = new ActiveDataProvider([
            'query' => Yii::$app->user->identity->getUrls()
        ]);
        
        return $this->render('ownList', [
            'dataProvider' => $dataProvider
        ]);
    }
    
    /**
     * отобразить все ссылки  для текущего юзера
     * @return type
     */
    public function actionList()
    {
        Yii::$app->user->setReturnUrl(\yii\helpers\Url::to());
        
        $filterModel = new UrlSearch;
        $dataProvider = $filterModel->search(Yii::$app->request->queryParams);
        
        return $this->render('list', [
            'filterModel' => $filterModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single Url model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        if (!Yii::$app->user->can('accessUrl', ['url' => $model])) {
            throw new ForbiddenHttpException;
        }
        
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Url model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Url();

        if ($model->load(Yii::$app->request->post())) {
            
            $model->user_id = Yii::$app->user->identity->id;
            
            if ($model->save()) {
                //return $this->redirect(['view', 'id' => $model->id]);
                return $this->goBack();
            }
            
        } else {
            
        }
        
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Url model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if (!Yii::$app->user->can('accessUrl', ['object' => $model])) {
            throw new ForbiddenHttpException;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //return $this->redirect(['own-list']);
            return $this->goBack();
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Url model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        if (!Yii::$app->user->can('accessUrl', ['url' => $model])) {
            throw new ForbiddenHttpException;
        }
        
        $model->delete();

        return $this->goBack();
        //return $this->redirect(['index']);
    }

    /**
     * Finds the Url model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Url the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Url::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
