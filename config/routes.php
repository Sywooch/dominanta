<?php

return [
    'login'               => 'site/login',
    'registration'        => 'site/registration',
    'logout'              => 'site/logout',
    'activate/<token:.*>' => 'site/activate',
    'restore/<token:.*>'  => 'site/restore',
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
    '<page:.*>' => 'site/page',
];