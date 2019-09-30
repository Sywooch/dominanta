<?php

return [
    'login'               => 'site/login',
    'registration'        => 'site/registration',
    'logout'              => 'site/logout',
    'activate/<token:.*>' => 'site/activate',
    'restore/<token:.*>'  => 'site/restore',
    'subscribe/<token:.*>'  => 'site/subscribe',
    'unsubscribe/<token:.*>'  => 'site/unsubscribe',
    [
        'pattern'  => 'manage/<controller>/<action:\w*>/<id:\d*>',
        'route'    => 'manage/<controller>/<action>',
        'defaults' => [
            'controller' => 'manage',
            'action'     => 'index',
            'id'         => 0,
        ],
    ],
    [
        'pattern'  => 'manage/<section>/<controller>/<action:\w*>/<id:\w*>',
        'route'    => 'manage/<section>/<controller>/<action>',
        'defaults' => [
            'controller' => 'manage',
            'action'     => 'index',
            'id'         => 0,
        ],
    ],


   // ''          => 'site/index',
    'shop' => 'shop/index',
    'shop/<url:.*>' => 'shop/index',
    'shopcart' => 'shopcart/index',
    'shopcart/result' => 'shopcart/result',
    'shopcart/success' => 'shopcart/success',
    'shopcart/fail' => 'shopcart/fail',
    'shopcart/<url:.*>' => 'shopcart/index',
    'account' => 'account/index',
    'account/<url:.*>' => 'account/index',
    '<page:.*>' => 'site/page',
];