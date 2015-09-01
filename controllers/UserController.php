<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\Profile;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\widgets\ActiveForm;
use yii\data\ActiveDataProvider;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['shop-reg', 'reg'],
                        'allow' => true,
                        'roles' => ['?']
                    ],
                    [
                        'actions' => ['shop-list', 'recommend', 'display'],
                        'allow' => true,
                        'roles' => [User::ROLE_USER]
                    ],
                    [
                        'actions' => ['profile', 'update-profile'],
                        'allow' => true,
                        'roles' => [User::ROLE_SHOP, User::ROLE_USER]
                    ],
                    [
                        'allow' => true,
                        'roles' => [User::ROLE_ADMIN]
                    ]
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex($role = User::ROLE_SHOP)
    {
        $searchModel = new UserSearch([
            'role' => $role
        ]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'role' => $role
        ]);
    }

    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User(['scenario'=>'shop_reg']);
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            
            $model->setPassword($model->password);
            
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
            
        }
        
        return $this->render('update', [
                'model' => $model,
            ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    /*public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }*/

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    
    public function actionProfile()
    {
        Yii::$app->user->setReturnUrl(\yii\helpers\Url::to());
        
        $attributes = [
            'email',
            'balance',
            'phone'
        ];
        
        if (Yii::$app->user->can(User::ROLE_SHOP)) {
            
            $items = [
                'profile.url:url',
                'profile.buyer_bonus',
                'profile.recommender_bonus',
            ];
            
            $attributes = \yii\helpers\ArrayHelper::merge($attributes, $items);
            
        }
        
        $attributes[] = 'created_at:datetime';
        
        return $this->render('profile', [
            'attributes' => $attributes
        ]);
    }
    
    public function actionUpdateProfile()
    {
        //$user = User::findOne(Yii::$app->user->identity->id);
        $user = Yii::$app->user->identity;
        $profile = Profile::find()->where(['user_id'=>$user->id])->one();
        
        if (Yii::$app->user->can(User::ROLE_SHOP) && $profile->load(Yii::$app->request->post()) && $profile->validate()) {
            $profile->save();
        }
        
        if ($user->load(Yii::$app->request->post()) && $user->save()) {
            return $this->goBack();
        }
        
        return $this->render('updateProfile', [
            'user' => $user,
            'profile' => $profile
        ]);
    }
    
    /**
     * отображение выбранного магазина
     * @param type $id
     * @return type
     */
    public function actionDisplay($id)
    {
        Yii::$app->user->setReturnUrl(\yii\helpers\Url::to());
        
        $model = $this->findModel($id);
                
        if ($id == 1 || !Yii::$app->authManager->checkAccess($model->id, User::ROLE_SHOP)) {
            throw new \yii\web\BadRequestHttpException('Магазин не найден');
        }
        
        $urlFilterModel = new \app\models\UrlSearch([
            'user_id' => $id
        ]);
        $urlDataProvider = $urlFilterModel->search(Yii::$app->request->queryParams);
        
        $commentDataProvider = new ActiveDataProvider([
            'query' => $model->getShopComments(),
            'sort' => [
                'defaultOrder' => ['created_at'=>SORT_DESC]
            ],
            'pagination' => [
                //'pageSize' => 1,
                'pageParam' => 'commentPage'
            ],
        ]);
        $comment = new \app\models\Comment([
            'user_id' => $model->id
        ]);
        
        return $this->render('display', [
            'model' => $model,
            'urlFilterModel' => $urlFilterModel,
            'urlDataProvider' => $urlDataProvider,
            'commentDataProvider' => $commentDataProvider,
            'comment' => $comment
        ]);
    }
    
    /**
     * вывод списка магазинов
     * @return type
     */
    public function actionShopList()
    {
        $searchModel = new UserSearch([
            'role' => User::ROLE_SHOP
        ]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $dataProvider->sort->attributes['url'] = [
            'asc' => ['profile.url' => SORT_ASC],
            'desc' => ['profile.url' => SORT_DESC]
        ];
        
        $dataProvider->sort->attributes['recommender_bonus'] = [
            'asc' => ['profile.recommender_bonus' => SORT_ASC],
            'desc' => ['profile.recommender_bonus' => SORT_DESC]
        ];
        
        $dataProvider->sort->attributes['buyer_bonus'] = [
            'asc' => ['profile.buyer_bonus' => SORT_ASC],
            'desc' => ['profile.buyer_bonus' => SORT_DESC]
        ];

        return $this->render('shopList', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
    
    /**
     * рекомендовать друзьям
     * @return type
     */
    public function actionRecommend()
    {
        $data = Yii::$app->request->post('data', '');
        
        if (!empty($data)) {
            
            \app\helpers\Recommend::send($data);
            
            Yii::$app->session->setFlash('success', 'Отправлено');
        }
        
        return $this->render('recommend');
    }
    
}
