<?php

use yii\helpers\Html;
use rmrevin\yii\fontawesome\component\Icon;
use yiister\gentelella\widgets\grid\GridView;
use app\models\ActiveRecord\Template;

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
                              ($rules[$page_model->modelName]['is_edit'] ? '{edit} ' : ' ').
                              ($rules[$page_model->modelName]['is_delete'] ? '{delete}' : '').
                              ($rules[$page_model->modelName]['is_delete'] ? '{restore} ' : ''),
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
                        return $model->status == $model::STATUS_DELETED ? '' : Html::a(new Icon('remove', ['class' => 'fa-lg']),
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
                        return $model->status != $model::STATUS_DELETED ? '' : Html::a(new Icon('undo', ['class' => 'fa-lg']),
                                      ['restore', 'id' => $model->id],
                                      ['title' => Yii::t('app', 'Restore'),
                                       'aria-label' => Yii::t('app', 'Restore'),
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
                'contentOptions' => ['class' => 'text-center'],
                'content' => function($data) {
                    return '{{{template|'.$data->id.'}}}';
                },
            ],
            [
                'attribute' => 'template_name',
            ],
            [
                'attribute' => 'layout',
            ],
        ],
    ]
);

?>

<div class="btn-group" role="group">
    <?= Html::a(new Icon('check').' '.Yii::t('app', 'Active records'), ['/manage/site/templates'], ['class' => 'btn btn-round btn-'.($status == $page_model::STATUS_ACTIVE ? 'success' : 'default')]) ?>
    <?= Html::a(new Icon('trash').' '.Yii::t('app', 'Deleted records'), ['/manage/site/templates', 'status' => $page_model::STATUS_DELETED], ['class' => 'btn btn-round btn-'.($status == $page_model::STATUS_DELETED ? 'success' : 'default')]) ?>
</div>
