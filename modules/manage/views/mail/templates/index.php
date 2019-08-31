<?php

use yii\helpers\Html;
use rmrevin\yii\fontawesome\component\Icon;
use yiister\gentelella\widgets\grid\GridView;

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
        'emptyText' => '<div class="well text-center"><h3>'.Yii::t('app', 'No records found').'</h3><div>'.$add_button.'</div></div>',
        'columns' => [
            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['width' => '80', 'class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center', 'style' => 'vertical-align: middle'],
                'template' => ($rules[$page_model->modelName]['is_edit'] ? '{fast} ' : ' ').
                              ($rules[$page_model->modelName]['is_edit'] ? '{edit}' : '').' '.
                              ($rules[$page_model->modelName]['is_delete'] ? '{delete}' : ''),
                'buttons' => [
                    'fast' => function ($url, $model) {
                        return Html::a(new Icon('magic', ['class' => 'fa-lg']), ['edit', 'id' => $model->id],
                                      ['title' => Yii::t('app', 'Fast edit'),
                                       'aria-label' => Yii::t('app', 'Fast edit'),
                                       'data' => [
                                          'tooltip' => 'true',
                                          'toggle' => 'modal',
                                          'target' => '.bs-example-modal-lg'
                                      ]]
                               );
                    },
                    'edit' => function ($url, $model) {
                        return Html::a(new Icon('pencil', ['class' => 'fa-lg']),
                                      ['edit', 'id' => $model->id],
                                      ['title' => Yii::t('app', 'Edit'),
                                       'aria-label' => Yii::t('app', 'Edit'),
                                       'data' => [
                                          'toggle' => 'tooltip',
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
                    'activate' => function ($url, $model) {
                        return $model->status == $model::STATUS_ACTIVE ? new Icon('check', ['class' => 'fa-lg']) :
                               Html::a(new Icon('check', ['class' => 'fa-lg']), ['activate', 'id' => $model->id],
                                      ['title' => Yii::t('app', 'Activate'),
                                       'aria-label' => Yii::t('app', 'Activate'),
                                       'data' => [
                                          'toggle' => 'tooltip',
                                          'pjax' => 0,
                                          'method' => 'post',
                                      ]]);
                    }
                 ],
            ],
            [
                'attribute' => 'id',
                'label' => 'ID',
                'headerOptions' => ['width' => '10'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'template_name',
            ],
            [
                'attribute' => 'slug',
            ],
        ],
    ]
);

?>

<div class="btn-group" role="group">

</div>
