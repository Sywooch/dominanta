<?php

namespace app\modules\manage;

use Yii;
use yii\base\Module;
use app\components\helpers\ModelsHelper;
use app\models\ActiveRecord\Mail;
use app\models\ActiveRecord\MailSetting;
use app\models\ActiveRecord\Option;
use app\models\ActiveRecord\Page;
use app\models\ActiveRecord\Role;
use app\models\ActiveRecord\Template;
use app\models\ActiveRecord\User;

class ManageModule extends Module
{
    protected $menu = [
        'Site' => [
            'icon' => 'globe',
            'Page' => [
                'icon'  => 'files-o',
                'url'   => '/manage/site/pages',
                'badge' => [['status' => Page::STATUS_ACTIVE], ['>=', 'status', Page::STATUS_INACTIVE]]
            ],
            'Template' => [
                'icon'  => 'file-o',
                'url'   => '/manage/site/templates',
                'badge' => ['status' => Template::STATUS_ACTIVE],
            ],
            'Css' => [
                'icon'  => 'css3',
                'url'   => '/manage/site/css',
                'badge' => [],
            ],
            'Js' => [
                'icon'  => 'file-code-o',
                'url'   => '/manage/site/js',
                'badge' => [],
            ],
            'Menu' => [
                'icon'  => 'list-alt',
                'url'   => '/manage/site/menu',
                'badge' => [],
            ],
            'Variable' => [
                'icon'  => 'code',
                'url'   => '/manage/site/vars',
                'badge' => [],
            ],
            'Option' => [
                'icon'  => 'sliders',
                'url'   => '/manage/site/settings',
                'badge' => false,
            ],
        ],
        'Access' => [
            'icon' => 'unlock-alt',
            'User' => [
                'url'   => '/manage/access/users',
                'icon'  => 'user',
                'badge' => ['status' => User::STATUS_ACTIVE],
            ],
            'Role' => [
                'url'   => '/manage/access/roles',
                'icon'  => 'users',
                'badge' => ['status' => Role::STATUS_ACTIVE],
            ]
        ],
        'Mail' => [
            'icon' => 'envelope',
            'MailSetting' => [
                'url' => '/manage/mail/settings',
                'icon' => 'at',
                'badge' => ['status' => MailSetting::STATUS_ACTIVE],
            ],
            'Mail' => [
                'url' => '/manage/mail/queue',
                'icon' => 'send-o',
                'badge' => [['status' => Mail::STATUS_INACTIVE], ['>=', 'status', Mail::STATUS_INACTIVE]],
            ],
        ],
    ];


    public function init()
    {
        parent::init();

        if (!Yii::$app->user->isGuest) {
            $this->params['user'] = Yii::$app->user->identity;
            $this->params['role'] = Yii::$app->user->identity->role;
            $this->params['rules'] = [];
            $this->params['options'] = [];

            $models = ModelsHelper::get();
            $all_models = $models;

            foreach (Yii::$app->user->identity->role->rules AS $rule) {
                $this->params['rules'][$rule['model']] = [
                    'is_view'    => $rule['is_view'],
                    'is_add'     => $rule['is_add'],
                    'is_edit'    => $rule['is_edit'],
                    'is_delete'  => $rule['is_delete'],
                ];

                unset($models[$rule['model']]);
            }

            foreach ($models AS $model_name => $model_data) {
                $this->params['rules'][$model_name] = [
                    'is_view'    => 0,
                    'is_add'     => 0,
                    'is_edit'    => 0,
                    'is_delete'  => 0,
                ];
            }

            foreach (Option::find()->all() AS $site_option) {
                $this->params['options'][$site_option->option] = $site_option->option_value;
            }

            Yii::$app->params['menu_access'] = [];

            foreach ($this->menu AS $first_level => $second_level) {
                $prepare_first_level = [
                    'label' => Yii::t('app', $first_level),
                    'icon'  => $second_level['icon'],
                    'url'   => '#',
                    'items' => [],
                ];

                unset($second_level['icon']);

                foreach ($second_level AS $model_name => $menu_item) {
                    if (!$this->params['rules'][$model_name]['is_view']) {
                        continue;
                    }

                    $model_class = $all_models[$model_name]['classname'];

                    $prepare_second_level = [
                        'label' => Yii::t('app', $all_models[$model_name]['realname']),
                        'icon'  => $menu_item['icon'],
                        'url'   => [$menu_item['url']],
                    ];

                    if ($menu_item['badge'] !== false) {
                        if (is_array($menu_item['badge'])) {
                            if (count($menu_item['badge']) == 0) {
                                $prepare_second_level['badge'] = $model_class::find()->count();
                            } elseif (count($menu_item['badge']) == 1) {
                                $prepare_second_level['badge'] = $model_class::find()->where($menu_item['badge'])->count();
                            } else {
                                $item_badges = [];

                                foreach ($menu_item['badge'] AS $one_badge) {
                                    $item_badges[] = $model_class::find()->where($one_badge)->count();
                                }

                                $prepare_second_level['badge'] = implode(' / ', $item_badges);
                            }
                        } else {
                            $prepare_second_level['badge'] = $menu_item['badge'];
                        }
                    }

                    $prepare_first_level['items'][] = $prepare_second_level;
                }

                if ($prepare_first_level['items']) {
                    Yii::$app->params['menu_access'][] = $prepare_first_level;
                }
            }
        }
    }
}