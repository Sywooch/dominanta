<?php

use yii\helpers\Html;
use yii\helpers\Url;
use rmrevin\yii\fontawesome\component\Icon;
use yiister\gentelella\widgets\grid\GridView;

//alt_title

$this->title = isset($model->id) ? Yii::t('app', $page_model->entityName).': '.Html::encode($model->category_name) : Yii::t('app', $page_model->entitiesName);

if (isset($model->id)) {
    $this->params['alt_title'] = Html::a(Yii::t('app', $page_model->entityName), ['/manage/market/categories']);
    $this->params['alt_title_small'] = Html::a($model->category_name, ['/manage/market/categories', 'cat_id' => $model->pid ? $model->pid : NULL]);
}

if ($rules[$page_model->modelName]['is_add']) {
    if (!isset($model->id) || !$model->countProducts) {
        $add_button = Html::a(new Icon('plus').' '.Yii::t('app', 'Add category'), ['edit', 'ProductCategory[pid]' => isset($model->id) ? $model->id : NULL], [
            'class' => 'btn btn-round btn-success',
            'data' => [
                'toggle' => 'modal',
                'target' => '.bs-example-modal-lg'
            ]
        ]);
    } else {
        $add_button = '';
    }
} else {
    $add_button = '';
}

if ($rules['Product']['is_add']) {
    $add_product_button = Html::a(new Icon('plus').' '.Yii::t('app', 'Add product'), ['/manage/market/products/edit'], [
        'class' => 'btn btn-round btn-success',
        'data' => [
            'toggle' => 'modal',
            'target' => '.bs-example-modal-lg'
        ]
    ]);
} else {
    $add_product_button = '';
}

$this->params['top_panel'] = $add_button;
$this->params['select_menu'] = Url::to(['/manage/market/categories']);

?>

<?= GridView::widget(
    [
        'dataProvider' => $data_provider,
        'filterModel' => $search,
        'hover' => true,
        'emptyText' => '<div class="well text-center"><h3>'.Yii::t('app', 'No records found').'</h3><div>'.$add_button.$add_product_button.'</div></div>',
        'columns' => [
            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['width' => '100', 'class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center', 'style' => 'vertical-align: middle'],
                'template' => ($rules[$page_model->modelName]['is_edit'] ? '{fast} ' : ' ').
                              ($rules[$page_model->modelName]['is_edit'] ? '{edit} ' : ' ').
                              ($rules[$page_model->modelName]['is_edit'] ? '{show} ' : ' ').
                              ($rules[$page_model->modelName]['is_edit'] ? '{hide} ' : ' ').
                              ($rules[$page_model->modelName]['is_edit'] ? '{photo} ' : ' ').
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
                    'show' => function ($url, $model) {
                        if ($model->status == $model::STATUS_ACTIVE) {
                            return '';
                        }

                        $title = ($model->status == $model::STATUS_INACTIVE ? Yii::t('app', 'Hidden') : Yii::t('app', 'Deleted')).
                                 '. '.Yii::t('app', 'Show');

                        return Html::a(new Icon('eye-slash', ['class' => 'fa-lg']),
                                      ['show', 'id' => $model->id],
                                      ['title' => $title,
                                       'aria-label' => $title,
                                       'data' => [
                                          'toggle' => 'tooltip',
                                      ]]
                              );
                    },
                    'hide' => function ($url, $model) {
                        if ($model->status == $model::STATUS_INACTIVE) {
                            return '';
                        }

                        $title = ($model->status == $model::STATUS_ACTIVE ? Yii::t('app', 'Published') : Yii::t('app', 'Deleted')).
                                 '. '.Yii::t('app', 'Hide');

                        return Html::a(new Icon('eye', ['class' => 'fa-lg']),
                                      ['hide', 'id' => $model->id],
                                      ['title' => $title,
                                       'aria-label' => $title,
                                       'data' => [
                                          'toggle' => 'tooltip',
                                      ]]
                              );
                    },
                    'photo' => function ($url, $model) {
                        return Html::a(new Icon('image', ['class' => 'fa-lg']), ['photo', 'id' => $model->id],
                                      ['title' => Yii::t('app', 'Photo'),
                                       'aria-label' => Yii::t('app', 'Photo'),
                                       'data' => [
                                          'tooltip' => 'true',
                                          'toggle' => 'modal',
                                          'target' => '.bs-example-modal-lg'
                                      ]]
                               );
                    },
                    'delete' => function ($url, $model) {
                        if ($model->status == $model::STATUS_DELETED) {
                            return '';
                        }

                        $title = ($model->status == $model::STATUS_ACTIVE ? Yii::t('app', 'Published') : Yii::t('app', 'Hidden')).
                                 '. '.Yii::t('app', 'Delete');

                        return Html::a(new Icon('remove', ['class' => 'fa-lg']),
                                      ['delete', 'id' => $model->id],
                                      ['title' => $title,
                                       'aria-label' => $title,
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
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'category_name',
                'content' => function($data) {
                    $count_subcats = $data->countAllSubcats;
                    return Html::a(Html::encode($data->category_name), [!$count_subcats['all'] && $count_subcats['products_all'] ? '/manage/market/products' : '', 'cat_id' => $data->id]);
                }
            ],
            [
                'attribute' => 'slug',
                'content' => function($data) {
                    return Html::a(Html::encode($data->slug), $data->catLink, ['target' => '_blank']);
                }

/*                'content' => function($data) {
                    return $data->path ? Html::a($data->path, $data->path, ['target' => '_blank']) : '';
                },*/
            ],
            [
                'encodeLabel' => false,
                'label' => Yii::t('app', 'Subcategories').'<br /><span style="font-size: 9px">'.Yii::t('app', 'Total').' / '.Yii::t('app', 'Published').'</span>',
                'content' => function($data) {
                    $count_subcats = $data->countAllSubcats;
                    return Html::a($count_subcats['all'].' / '.$count_subcats['published'], [!$count_subcats['all'] && $count_subcats['products_all'] ? '/manage/market/products' : '', 'cat_id' => $data->id]);
                },
            ],
            [
                'encodeLabel' => false,
                'label' => Yii::t('app', 'Products').'<br /><span style="font-size: 9px">'.Yii::t('app', 'Total').' / '.Yii::t('app', 'Published').'</span>',
                'content' => function($data) {
                    $count_subcats = $data->countAllSubcats;
                    return Html::a($count_subcats['products_all'].' / '.$count_subcats['products_published'], [!$count_subcats['all'] && $count_subcats['products_all'] ? '/manage/market/products' : '', 'cat_id' => $data->id]);
                },
            ],
        ],
    ]
);

?>

<?php if (isset($model->id)) { ?>
<div>
    <?= Html::a(new Icon('arrow-up').' '.Yii::t('app', 'Parent section'), ['/manage/market/categories', 'cat_id' => $model->pid ? $model->pid : NULL], ['class' => 'btn btn-round btn-success']) ?>
</div>
<?php } ?>

<div class="btn-group" role="group">
    <?= Html::a(new Icon('check').' '.Yii::t('app', 'Active records'), ['/manage/market/categories', 'cat_id' => isset($model->id) ? $model->id : NULL], ['class' => 'btn btn-round btn-'.($status != $page_model::STATUS_DELETED ? 'success' : 'default')]) ?>
    <?= Html::a(new Icon('trash').' '.Yii::t('app', 'Deleted records'), ['/manage/market/categories', 'status' => $page_model::STATUS_DELETED, 'cat_id' => isset($model->id) ? $model->id : NULL], ['class' => 'btn btn-round btn-'.($status == $page_model::STATUS_DELETED ? 'success' : 'default')]) ?>
</div>
