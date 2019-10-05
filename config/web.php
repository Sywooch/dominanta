<?php

$params = require __DIR__ . '/params.php';
$db     = require __DIR__ . '/db.php';
$routes = require(__DIR__ . '/routes.php');

$config = [
    'id' => substr($_SERVER['SERVER_NAME'], 0, strpos($_SERVER['SERVER_NAME'], '.')),
    'basePath' => dirname(__DIR__),
    'timeZone' => 'Europe/Minsk',
    'sourceLanguage' => 'en-US',
    'language' => 'ru-RU',
    'bootstrap' => ['log', 'user_settings', 'site_options'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
        'manage' => [
            'class' => 'app\modules\manage\ManageModule',
        ],
    ],
    'components' => [
        'reCaptcha' => [
            'name' => 'reCaptcha',
            'class' => 'himiklab\yii2\recaptcha\ReCaptchaConfig',
            //'siteKeyV3' => isset($params['reCaptcha']) && isset($params['reCaptcha']['siteKey']) ? $params['reCaptcha']['siteKey'] : '',
            //'secretV3'  => isset($params['reCaptcha']) && isset($params['reCaptcha']['secret']) ? $params['reCaptcha']['secret'] : '',
            'siteKeyV2' => isset($params['reCaptcha']) && isset($params['reCaptcha']['siteKey']) ? $params['reCaptcha']['siteKey'] : '',
            'secretV2'  => isset($params['reCaptcha']) && isset($params['reCaptcha']['secret']) ? $params['reCaptcha']['secret'] : '',
        ],
        'robokassa' => [
            'class' => '\robokassa\Merchant',
            'baseUrl' => 'https://auth.robokassa.ru/Merchant/Index.aspx',
            'sMerchantLogin' => isset($params['robokassa']) && isset($params['robokassa']['sMerchantLogin']) ? $params['robokassa']['sMerchantLogin'] : '',
            'sMerchantPass1' => isset($params['robokassa']) && isset($params['robokassa']['sMerchantPass1']) ? $params['robokassa']['sMerchantPass1'] : '',
            'sMerchantPass2' => isset($params['robokassa']) && isset($params['robokassa']['sMerchantPass2']) ? $params['robokassa']['sMerchantPass2'] : '',
            'isTest' => isset($params['robokassa']) && isset($params['robokassa']['isTest']) ? $params['robokassa']['isTest'] : '',
        ],
        'formatter' => [
            'defaultTimeZone' => 'Europe/Moscow',
            'dateFormat' => 'dd.MM.yyyy',
            'datetimeFormat' => 'dd.MM.yyyy HH:mm:ss',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => 'RUR',
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => md5(sha1($_SERVER['SERVER_NAME'])),
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\ActiveRecord\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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
        'user_settings' => [
            'class' => 'app\components\bootstrap\ActionUserSettings',
        ],
        'site_options' => [
            'class' => 'app\components\bootstrap\SiteOptions',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => $routes,
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'fileMap' => [
                        'app' => 'app.php',
                    ],
                ],
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
