<?php

namespace app\controllers;

use Yii;
use app\models\Comment;
use app\models\CommentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;
use yii\data\ActiveDataProvider;

/**
 * CommentController implements the CRUD actions for Comment model.
 */
class CommentController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => [User::ROLE_SHOP]
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => [User::ROLE_USER]
                    ],
                    [
                        'actions' => ['list'],
                        'allow' => true,
                        'roles' => [User::ROLE_USER, User::ROLE_SHOP]
                    ],
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
     * Lists all Comment models.
     * @return mixed
     */
    public function actionIndex()
    {
        Yii::$app->user->setReturnUrl(\yii\helpers\Url::to());
        
        $searchModel = new CommentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * отображение комментариев магазина или юзера
     * @return type
     */
    public function actionList()
    {
        
        if (Yii::$app->user->can(User::ROLE_SHOP)) {
            $query = Yii::$app->user->identity->getShopComments();
        } else if (Yii::$app->user->can(User::ROLE_USER)) {
            $query = Yii::$app->user->identity->getAuthorComments();
        } else {
            throw new \yii\web\BadRequestHttpException;
        }
        
        Yii::$app->user->setReturnUrl(\yii\helpers\Url::to());
        
        //$searchModel = new CommentSearch();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['created_at'=>SORT_DESC]
            ]
        ]);

        return $this->render('list', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Comment model.
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
     * Creates a new Comment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($shop_id)
    {
        $model = new Comment();

        if ($model->load(Yii::$app->request->post())) {
            
            $model->author_id = Yii::$app->user->identity->id;
            $model->user_id = $shop_id;
            
            if ($model->save()) {
                return $this->goBack();
            }
            
        } else {
            
        }
        
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Comment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';
        
        if (!Yii::$app->user->can('accessComment', ['object' => $model])) {
            throw new \yii\web\ForbiddenHttpException;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->goBack();
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Comment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        if (!Yii::$app->user->can('accessComment', ['object' => $model])) {
            throw new \yii\web\ForbiddenHttpException;
        }
        
        $model->delete();

        return $this->goBack();
    }

    /**
     * Finds the Comment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Comment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Comment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
