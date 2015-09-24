<?php

namespace app\helpers;

use Yii;
use app\models\User;
use yii\helpers\ArrayHelper;

class Menu
{
    public static function getItems()
    {
        
        $menu = [
            ['label' => 'Главная', 'url' => ['site/index']]
        ];
        
        if (Yii::$app->user->isGuest) {
            
            $menu[] = [
                'label' => 'Регистрация', 
                'items' => [
                    ['label' => 'Магазин', 'url' => ['registration/shop']],
                    ['label' => 'Пользователь', 'url' => ['registration/user']]
                ]
            ];
            
        } else {
        
            if (Yii::$app->user->can(User::ROLE_ADMIN)) {
            
                $items = [
                    [
                        'label' => 'Активные магазины', 'url' => ['user/index', 'role'=>User::ROLE_SHOP]
                    ],
                    [
                        'label' => 'Магазины на модерации', 'url' => ['moderate-shop/index']
                    ],
                    [
                        'label' => 'Статусы для магазинов', 'url' => ['shop-status/index']
                    ],
                ];

            } else {

                $items = [
                    [
                        'label' => 'Профиль', 'url' => ['user/profile']
                    ],
                    [
                        'label' => 'Транзакции', 'url' => ['purchase/user-list']
                    ]
                ];

                if (Yii::$app->user->can(User::ROLE_USER)) {
                    
//                    $items = ArrayHelper::merge($items, [
//                        [
//                            'label' => 'Магазины', 'url' => ['user/shop-list']
//                        ],
//                        [
//                            'label' => 'Покупки', 'url' => ['purchase/user-list']
//                        ],
//                        [
//                            'label' => 'Покупки по рекомендации', 'url' => ['purchase/affiliate-list']
//                        ],
//                        [
//                            'label' => 'Порекомендовать', 'url' => ['user/recommend']
//                        ]
//                    ]);

                } elseif (Yii::$app->user->can(User::ROLE_SHOP)) {

                    $items = ArrayHelper::merge($items, [
                        [
                            'label' => 'Ссылки', 'url' => ['url/own-list']
                        ],
                        [
                            'label' => 'Покупки пользователей', 'url' => ['purchase/shop-list']
                        ]
                    ]);

                }

            }
            
            $menu[] = [
                'label' => Yii::$app->user->identity->email, 'items'=>$items
            ];
        
        }
        
        $menu[] = Yii::$app->user->isGuest ?
                ['label' => 'Вход', 'url' => ['site/login']] :
                ['label' => 'Выход', 'url' => ['site/logout'], 'linkOptions' => ['data-method' => 'post']];
        
        
        return $menu;
    }
}