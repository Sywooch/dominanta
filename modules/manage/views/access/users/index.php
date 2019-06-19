<?php

use yii\helpers\Html;
use rmrevin\yii\fontawesome\component\Icon;
use yiister\gentelella\widgets\grid\GridView;
use app\components\helpers\TzHelper;
use app\models\ActiveRecord\Role;

$this->title = Yii::t('app', $page_model->entitiesName);

if ($rules[$page_model->modelName]['is_add']) {
    $add_button = Html::a(new Icon('plus').' '.Yii::t('app', 'Add'), ['edit'], [
        'class' => 'btn btn-round btn-success',
        'data' => [
            'toggle' => 'modal',
            'target' => '.bs-example-modal-lg'
        ]
    ]);
} else {
    $add_button = '';
}

$this->params['top_panel'] = $add_button;

?>

<?= GridView::widget(
    [
        'dataProvider' => $data_provider,
        'filterModel' => $search,
        'hover' => true,
        'emptyText' => '<div class="well text-center"><h3>'.Yii::t('app', 'No users found').'</h3><div>'.($status == $page_model::STATUS_ACTIVE ? $add_button : '').'</div></div>',
        'columns' => [
            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['width' => '50', 'class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center', 'style' => 'vertical-align: middle'],
                'template' => $status == $page_model::STATUS_ACTIVE ?
                    ($rules[$page_model->modelName]['is_edit'] ? '{edit}' : '').' '.($rules[$page_model->modelName]['is_delete'] ? '{delete}' : '')
                    :
                    ($rules[$page_model->modelName]['is_delete'] ? '{restore}' : ''),
                'buttons' => [
                    'edit' => function ($url, $model) {
                        return Html::a(new Icon('pencil', ['class' => 'fa-lg']),
                                      ['edit', 'id' => $model->id],
                                      ['title' => Yii::t('app', 'Edit'),
                                       'data' => [
                                          'tooltip' => 'true',
                                          'toggle' => 'modal',
                                          'target' => '.bs-example-modal-lg'
                                      ]]
                              );
                    },
                    'delete' => function ($url, $model) {
                        return Html::a(new Icon('remove', ['class' => 'fa-lg']),
                                      ['delete', 'id' => $model->id],
                                      ['title' => Yii::t('app', 'Delete'),
                                       'aria-label' => Yii::t('app', 'Delete'),
                                       'data' => [
                                          'toggle' => 'tooltip',
                                          'pjax' => 0,
                                          'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                          'method' => 'post',
                                      ]]
                               );
                    },
                    'restore' => function ($url, $model) {
                        return Html::a(new Icon($model->status == $model::STATUS_INACTIVE ? 'check' : 'undo', ['class' => 'fa-lg']),
                                      ['restore', 'id' => $model->id],
                                      ['title' => Yii::t('app', $model->status == $model::STATUS_INACTIVE ? 'Activate' : 'Restore'),
                                       'aria-label' => Yii::t('app', $model->status == $model::STATUS_INACTIVE ? 'Activate' : 'Restore'),
                                       'data' => [
                                          'toggle' => 'tooltip',
                                          'pjax' => 0,
                                          'method' => 'post',
                                      ]]
                               );
                    },
                 ],
            ],
            [
                'attribute' => 'id',
                'label' => 'ID',
                'headerOptions' => ['width' => '10'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'realname',
            ],
            [
                'attribute' => 'email',
            ],
            [
                'attribute' => 'phone',
            ],
            [
                'attribute' => 'role_id',
                'content' => function($data) {
                    return $data->role->role_name;
                },
                'filter' => Role::find()->select(['role_name', 'id'])
                                        ->where(['status' => Role::STATUS_ACTIVE])
                                        ->indexBy('id')
                                        ->column(),
            ],
            [
                'attribute' => 'language',
                'filter' => [
                    'ru-RU' => 'ru-RU',
                    'en-US' => 'en-US',
                ]
            ],
            [
                'attribute' => 'timeZone',
                'filter' => TzHelper::getZones(),
            ],
            [
                'attribute' => 'create_time',
                'format' =>  ['date', 'php:d.m.Y H:i:s'],
                'headerOptions' => ['width' => '100'],
            ],
            [
                'attribute' => 'last_activity',
                'format' =>  ['date', 'php:d.m.Y H:i:s'],
                'headerOptions' => ['width' => '100'],
            ],
        ],
    ]
);

?>

<div class="btn-group" role="group">
    <?= Html::a(new Icon('check').' '.Yii::t('app', 'Active records'), ['/manage/access/users/'], ['class' => 'btn btn-round btn-'.($status == $page_model::STATUS_ACTIVE?'success':'default')]) ?>
    <?= Html::a(new Icon('ban').' '.Yii::t('app', 'Inactive records'), ['/manage/access/users/', 'status' => $page_model::STATUS_INACTIVE], ['class' => 'btn btn-round btn-'.($status == $page_model::STATUS_INACTIVE?'success':'default')]) ?>
    <?= Html::a(new Icon('trash').' '.Yii::t('app', 'Deleted records'), ['/manage/access/users/', 'status' => $page_model::STATUS_DELETED], ['class' => 'btn btn-round btn-'.($status == $page_model::STATUS_DELETED?'success':'default')]) ?>
</div>
