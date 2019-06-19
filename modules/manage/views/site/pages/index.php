<?php

use yii\helpers\Html;
use rmrevin\yii\fontawesome\component\Icon;
use yiister\gentelella\widgets\grid\GridView;
use app\models\ActiveRecord\Page;

$this->title = Yii::t('app', $page_model->entitiesName);

if ($rules[$page_model->modelName]['is_add']) {
    $add_button = Html::a(new Icon('plus').' '.Yii::t('app', 'Add'), ['edit',  'Page[pid]' => $pid], [
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
                'headerOptions' => ['width' => '100', 'class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center', 'style' => 'vertical-align: middle'],
                'template' => ($rules[$page_model->modelName]['is_edit'] ? '{fast} ' : ' ').
                              ($rules[$page_model->modelName]['is_edit'] ? '{edit} ' : ' ').
                              ($rules[$page_model->modelName]['is_add'] ? '{add} ' : ' ').
                              ($rules[$page_model->modelName]['is_delete'] ? '{delete} ' : ' '),
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
                    'add' => function ($url, $model) {
                        return Html::a(new Icon('plus', ['class' => 'fa-lg']),
                                      ['edit', 'Page[pid]' => $model->id],
                                      ['title' => Yii::t('app', 'Add'),
                                       'aria-label' => Yii::t('app', 'Add'),
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
                 ],
            ],
            [
                'attribute' => 'id',
                'label' => 'ID',
                'headerOptions' => ['width' => '10'],
                'contentOptions' => ['class' => 'text-center'],
                'content' => function($data) {
                    return '{{{page|'.$data->id.'}}}';
                },
            ],
            [
                'attribute' => 'status',
                'headerOptions' => ['width' => '10'],
                'contentOptions' => ['class' => 'text-center'],
                'content' => function($data) {
                    if ($data->status == $data::STATUS_DELETED) {
                        return new Icon('trash', ['class' => 'fa-lg']);
                    }

                    if ($data->status == $data::STATUS_INACTIVE) {
                        return new Icon('eye-slash', ['class' => 'fa-lg']);
                    }

                    if ($data->status == $data::STATUS_ACTIVE) {
                        return new Icon('eye', ['class' => 'fa-lg']);
                    }
                },
            ],
            [
                'attribute' => 'page_name',
                'content' => function($data) {
                    if ($data->subpagesCount) {
                        return Html::a($data->page_name.' '.(new Icon('share')), ['/manage/site/pages', 'pid' => $data->id]);
                    } else {
                        return $data->page_name;
                    }
                },
            ],
            [
                'attribute' => 'slug',
                'content' => function($data) {
                    return Html::a($data->absoluteUrl, $data->absoluteUrl, ['target' => '_blank']);
                },
            ],
        ],
    ]
);

?>

<?php if ($pid) { ?>
<div>
    <?= Html::a(new Icon('arrow-up').' '.Yii::t('app', 'Parent section'), ['/manage/site/pages', 'pid' => Page::findOne($pid)->pid], ['class' => 'btn btn-round btn-success']) ?>
</div>
<?php } ?>

<div class="btn-group" role="group">
    <?= Html::a(new Icon('check').' '.Yii::t('app', 'Active records'), ['/manage/site/pages', 'pid' => $pid], ['class' => 'btn btn-round btn-'.($status != Page::STATUS_DELETED?'success':'default')]) ?>
    <?= Html::a(new Icon('trash').' '.Yii::t('app', 'Deleted records'), ['/manage/site/pages', 'status' => Page::STATUS_DELETED, 'pid' => $pid], ['class' => 'btn btn-round btn-'.($status == Page::STATUS_DELETED?'success':'default')]) ?>
</div>
