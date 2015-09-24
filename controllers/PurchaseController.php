<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\Purchase;
use app\models\PurchaseSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use app\models\Url;

/**
 * PurchaseController implements the CRUD actions for Purchase model.
 */
class PurchaseController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['?', '@']
                    ],
                    [
                        'actions' => ['user-list', 'affiliate-list', 'complaint'],
                        'allow' => true,
                        'roles' => [User::ROLE_USER]
                    ],
                    [
                        'actions' => ['shop-list', 'update', 'delete'],
                        'allow' => true,
                        'roles' => [User::ROLE_SHOP]
                    ],
                    [
                        'allow' => true,
                        'roles' => [User::ROLE_ADMIN]
                    ]
                ]
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
     * Lists all Purchase models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PurchaseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * покупки пользователя
     * @return type
     */
    public function actionUserList()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Purchase::find()
                ->where(['user_id'=>Yii::$app->user->identity->id])
                ->orWhere(['affiliate_id'=>Yii::$app->user->identity->id])
                ->orderBy('created_at DESC')
        ]);
        
        return $this->render('userList', [
            'dataProvider' => $dataProvider
        ]);
    }
    
    /**
     * покупки у магазина
     * @return type
     */
    public function actionShopList()
    {
        Yii::$app->user->setReturnUrl(\yii\helpers\Url::to());
        
        $query = Yii::$app->user->identity->getShopPurchases();
        $query->joinWith(['user']);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['created_at'=>SORT_DESC]
            ]
        ]);
        
        $dataProvider->sort->attributes['email'] = [
            'asc' => ['user.email' => SORT_ASC],
            'desc' => ['user.email' => SORT_DESC]
        ];
        
        $dataProvider->sort->attributes['phone'] = [
            'asc' => ['user.phone' => SORT_ASC],
            'desc' => ['user.phone' => SORT_DESC]
        ];
        
        return $this->render('shopList', [
            'dataProvider' => $dataProvider
        ]);
    }
    
    /**
     * покупки пользователя по рекомендации
     * @return type
     */
    public function actionAffiliateList()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Yii::$app->user->identity->getAffiliatePurchases()->orderBy('created_at DESC')
        ]);
        
        return $this->render('affiliateList', [
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single Purchase model.
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
     * переход покупателя по реф ссылке
     * @param type $affiliate_id рекомендатель
     * @param type $url_id ид ссылки
     * @return type
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionCreate($affiliate_id, $url_id)
    {        
        $user_id = null;
        
        if (!Yii::$app->user->isGuest) {
            $user_id = Yii::$app->user->identity->id;
        } else {
            $user_id = Yii::$app->request->cookies->getValue('user_id');
        }
        
        if ($user_id === null) {
            return $this->redirect(['registration/user', 'affiliate_id'=>$affiliate_id, 'url_id'=>$url_id]);
        } else {
            
            $url = Url::findOne($url_id);
            
            if (!$url) {
                throw new \yii\web\NotFoundHttpException('Урл не найден');
            }
            
            $user = User::findOne($user_id);
            $user->purchase($affiliate_id, $url);
            
            return $this->redirect($url->link);
        }
    }

    /**
     * Updates an existing Purchase model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if ($model->shop_id != Yii::$app->user->identity->id) {
            throw new \yii\web\BadRequestHttpException;
        }

        if ($model->load(Yii::$app->request->post())) {
            
            $model->status = \app\models\Paylog::STATUS_SUCCESS;
            
            if ($model->save()) {
                $model->proceedBalance();
                return $this->goBack();
            }
            
        } else {
            
        }
        
        return $this->render('update', [
            'model' => $model,
        ]);
        
    }

    /**
     * Deletes an existing Purchase model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        if ($model->shop_id != Yii::$app->user->identity->id) {
            throw new \yii\web\BadRequestHttpException;
        }
        
        $model->delete();

        return $this->goBack();
        //return $this->redirect(['index']);
    }

    /**
     * Finds the Purchase model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Purchase the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Purchase::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionComplaint()
    {
        $purchase = Purchase::findOne(Yii::$app->request->post('id'));
        
        
        if (!$purchase || !isset($purchase->shop))
            return;
        
        Yii::$app->mailer->compose('complaint', ['purchase'=>$purchase, 'user'=>Yii::$app->user->identity])
            ->setFrom(Yii::$app->params['emailFrom'])
            ->setTo([$purchase->shop->email])
            ->setSubject('Жалоба')
            ->send();
    }
}
