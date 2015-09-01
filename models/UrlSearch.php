<?php

namespace app\models;

use Yii;
use app\models\Url;
use yii\data\ActiveDataProvider;

class UrlSearch extends Url
{
    public function rules()
    {
        return [
            [['user_id', 'link', 'name'], 'safe']
        ];
    }
    
    public function search($params)
    {        
        $query = Url::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                //'pageSize' => 1,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'user_id' => $this->user_id
        ]);

        $query->andFilterWhere(['like', 'link', $this->link])
              ->andFilterWhere(['like', 'name', $this->name]);
        
        $dependency = new \yii\caching\DbDependency([
            'sql' => 'SELECT MAX(updated_at) FROM url',
            'reusable' => true
        ]);
        
        Yii::$app->db->cache(function($db) use ($dataProvider) {
            
            $dataProvider->prepare();
            
        }, Yii::$app->params['cacheExpire'], $dependency);
        
        return $dataProvider;
    }
}