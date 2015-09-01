<?php

$params = require(__DIR__ . '/params.php');

if (file_exists(__DIR__ . '/db.local.php')) {
    $db = require(__DIR__ . '/db.local.php');
} else {
    $db = require(__DIR__ . '/db.php');
}

$config = [
    'id' => 'bonus',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru',
    'name' => 'bonus',
    'components' => [
        
        'robokassa' => [
            'class' => '\robokassa\Merchant',
            'sMerchantLogin' => '',
            'sMerchantPass1' => '',
            'sMerchantPass2' => '',
        ],
        
        'sms' => [
            'class'    => 'ladamalina\smsc\Smsc',
            'login'     => 'sms-yslugi',  // login
            'password'   => '4bgfb76ghy87', // plain password or lowercase password MD5-hash
            'post' => true, // use http POST method
            'https' => true,    // use secure HTTPS connection
            'charset' => 'utf-8',   // charset: windows-1251, koi8-r or utf-8 (default)
            'debug' => false,    // debug mode
        ],
        
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'cache' => 'cache'
        ],
        
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'j-zelZR_zUbcJycszLdoWs4Pz_1R8VDa',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SendmailTransport'
            ],
            'useFileTransport' => false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'rules' => [
                
            ]
            //'showScriptName' => false,
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
