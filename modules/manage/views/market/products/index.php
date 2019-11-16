<?php

use yii\helpers\Html;
use yii\helpers\Url;
use rmrevin\yii\fontawesome\component\Icon;
use yiister\gentelella\widgets\grid\GridView;

//alt_title

$this->title = Yii::t('app', $page_model->entitiesName);

if ($category) {
    //$this->params['alt_title'] = Html::a(Yii::t('app', $page_model->entityName), ['/manage/market/categories']);
    $this->params['alt_title_small'] = Html::a($category->category_name, ['/manage/market/categories', 'cat_id' => $category->pid]);
}

if ($rules[$page_model->modelName]['is_add']) {
    $add_button = Html::a(new Icon('plus').' '.Yii::t('app', 'Add product'), ['edit', 'Product[cat_id]' => $category ? $category->id : NULL], [
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
$this->params['select_menu'] = Url::to(['/manage/market/products']);

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
                'headerOptions' => ['width' => '120', 'class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center', 'style' => 'vertical-align: middle'],
                'template' => ($rules[$page_model->modelName]['is_edit'] ? '{fast} ' : ' ').
                              ($rules[$page_model->modelName]['is_edit'] ? '{edit} ' : ' ').
                              ($rules[$page_model->modelName]['is_edit'] ? '{show} ' : ' ').
                              ($rules[$page_model->modelName]['is_edit'] ? '{hide} ' : ' ').
                              ($rules[$page_model->modelName]['is_edit'] ? '{photos} ' : ' ').
                              ($rules[$page_model->modelName]['is_edit'] ? '{labels} ' : ' ').
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
                    'photos' => function ($url, $model) {
                        return Html::a(new Icon('image', ['class' => 'fa-lg']),
                                      ['photos', 'id' => $model->id],
                                      ['title' => Yii::t('app', 'Photos'),
                                       'aria-label' => Yii::t('app', 'Photos'),
                                       'data' => [
                                          'tooltip' => 'true',
                                          'toggle' => 'modal',
                                          'target' => '.bs-example-modal-lg'
                                      ]]
                              );
                    },
                    'labels' => function ($url, $model) {
                        return Html::a(new Icon('tag', ['class' => 'fa-lg']),
                                      ['labels', 'id' => $model->id],
                                      ['title' => Yii::t('app', 'Labels'),
                                       'aria-label' => Yii::t('app', 'Labels'),
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
                'label' => '',
                'content' => function($data) {
                    return $data->mainPhoto ? Html::a(
                            Html::img(str_replace(Yii::getAlias('@webroot'), '', $data->getPreview($data->mainPhoto, 150, 150))),
                            str_replace(Yii::getAlias('@webroot'), '', $data->mainPhoto),
                            ['class' => 'multiple_image', 'rel' => 'gallery_photos']
                           )
                        : '';
                }
            ],
            [
                'attribute' => 'product_name',
                'encodeLabel' => false,
                'label' => Yii::t('app', 'Product name').'<br />'.Yii::t('app', 'Slug'),
                'content' => function($data) {
                    return Html::encode($data->product_name).'<br /><br /><i>'.Html::a(Html::encode($data->slug), $data->productLink, ['target' => '_blank']).'</i>';
                }
            ],
            [
                'attribute' => 'ext_code',
            ],
            [
                'attribute' => 'price',
                'content' => function($data) {
                    return Yii::$app->formatter->asDecimal(floatval($data->price), 2).' '.(new Icon('ruble'))
                           .($data->old_price ? '<br /><span style="text-decoration: line-through">'.Yii::$app->formatter->asDecimal(floatval($data->old_price), 2).' '.(new Icon('ruble').'</span>') : '');
                }
            ],
            [
                'attribute' => 'vendor_id',
                'enableSorting' => false,
                'filter' => false,
                'content' => function($data) {
                    return $data->vendor ? Html::encode($data->vendor->title) : '<i>'.Yii::t('app', 'Not specified').'</i>';
                }
            ],
/*            [
                'encodeLabel' => false,
                'label' => Yii::t('app', 'Subcategories').'<br /><span style="font-size: 9px">'.Yii::t('app', 'Total').' / '.Yii::t('app', 'Published').'</span>',
                'content' => function($data) {
                    $count_subcats = $data->countAllSubcats;
                    return Html::a($count_subcats['all'].' / '.$count_subcats['published'], ['', 'cat_id' => $data->id]);
                },
            ],
            [
                'encodeLabel' => false,
                'label' => Yii::t('app', 'Products').'<br /><span style="font-size: 9px">'.Yii::t('app', 'Total').' / '.Yii::t('app', 'Published').'</span>',
                'content' => function($data) {
                    $count_subcats = $data->countAllSubcats;
                    return Html::a($count_subcats['products_all'].' / '.$count_subcats['products_published'], ['', 'cat_id' => $data->id]);
                },
            ],*/
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
