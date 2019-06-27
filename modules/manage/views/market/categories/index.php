<?php

use yii\helpers\Html;
use yii\helpers\Url;
use rmrevin\yii\fontawesome\component\Icon;
use yiister\gentelella\widgets\grid\GridView;

$this->title = isset($model->id) ? Yii::t('app', $page_model->entityName).': '.Html::encode($model->category_name) : Yii::t('app', $page_model->entitiesName);

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
                'headerOptions' => ['width' => '80', 'class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center', 'style' => 'vertical-align: middle'],
                'template' => ($rules[$page_model->modelName]['is_edit'] ? '{fast} ' : ' ').
                              ($rules[$page_model->modelName]['is_edit'] ? '{edit} ' : ' ').
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
                 ],
            ],
            [
                'attribute' => 'id',
                'label' => 'ID',
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'category_name',
            ],
            [
                'attribute' => 'slug',
/*                'content' => function($data) {
                    return $data->path ? Html::a($data->path, $data->path, ['target' => '_blank']) : '';
                },*/
            ],
            [
                'label' => Yii::t('app', 'Content'),
                'content' => function($data) {
                    $count_subcats = $data->countSubcats;
                    $count_products = $data->countProducts;

                    return $count_subcats ?
                        Html::a(Yii::t('app', 'Subcategories').' ('.$count_subcats.')', ['index', 'cat_id' => $data->id])
                        : (
                          $count_products ?
                            Html::a(Yii::t('app', 'Products').' ('.$count_products.')', ['index', 'cat_id' => $data->id])
                            :
                          Html::a(Yii::t('app', 'Empty category'), ['index', 'cat_id' => $data->id])
                        );

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
</div>
