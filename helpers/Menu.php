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
                        'label' => 'Пользователи', 'url' => ['user/index', 'role'=>User::ROLE_USER]
                    ],
                    [
                        'label' => 'Магазины', 'url' => ['user/index', 'role'=>User::ROLE_SHOP]
                    ],
                    [
                        'label' => 'Ссылки', 'url' => ['url/index']
                    ],
                    [
                        'label' => 'Комментарии', 'url' => ['comment/index']
                    ],
                    [
                        'label' => 'Платежи', 'url' => ['paylog/index']
                    ],
                    [
                        'label' => 'Покупки', 'url' => ['purchase/index']
                    ],
                    [
                        'label' => 'Пополнить счет', 'url' => ['paylog/create']
                    ]
                ];

            } else {

                $items = [
                    [
                        'label' => 'Профиль', 'url' => ['user/profile']
                    ],
                    [
                        'label' => 'Комментарии', 'url' => ['comment/list']
                    ],
                    [
                        'label' => 'Платежи', 'url' => ['paylog/list']
                    ]
                ];

                if (Yii::$app->user->can(User::ROLE_USER)) {
                    
                    $items = ArrayHelper::merge($items, [
                        [
                            'label' => 'Магазины', 'url' => ['user/shop-list']
                        ],
                        [
                            'label' => 'Покупки', 'url' => ['purchase/user-list']
                        ],
                        [
                            'label' => 'Покупки по рекомендации', 'url' => ['purchase/affiliate-list']
                        ],
                        [
                            'label' => 'Порекомендовать', 'url' => ['user/recommend']
                        ]
                    ]);

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