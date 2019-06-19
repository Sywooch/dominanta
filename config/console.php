<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'site-console',
    'basePath' => dirname(__DIR__),
    'timeZone' => 'Europe/Moscow',
    'sourceLanguage' => 'en-US',
    'language' => 'ru-RU',
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'formatter' => [
            'defaultTimeZone' => 'Europe/Moscow',
            'dateFormat' => 'dd.MM.yyyy',
            'datetimeFormat' => 'dd.MM.yyyy HH:mm:ss',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => 'RUR',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
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
        'db' => $db,
    ],
    'params' => $params,
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
