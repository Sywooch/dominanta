<?php

use yii\helpers\Html;
use yii\helpers\Url;
use rmrevin\yii\fontawesome\component\Icon;
use yiister\gentelella\widgets\grid\GridView;

//alt_title

$this->title = Yii::t('app', $page_model->entitiesName);

$add_button = '';


$this->params['top_panel'] = $add_button;
$this->params['select_menu'] = Url::to(['/manage/market/reviews']);

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
                'template' => ($rules[$page_model->modelName]['is_edit'] ? '{approve} ' : ' ').
                              ($rules[$page_model->modelName]['is_edit'] ? '{delete}' : ''),
                'buttons' => [
                    'approve' => function ($url, $model) {
                        if ($model->status == $model::STATUS_ACTIVE) {
                            return '';
                        }

                        $title = ($model->status == $model::STATUS_INACTIVE ? Yii::t('app', 'Hidden') : Yii::t('app', 'Deleted')).
                                 '. '.Yii::t('app', 'Show');

                        return Html::a(new Icon('eye', ['class' => 'fa-lg']),
                                      ['approve', 'id' => $model->id],
                                      ['title' => $title,
                                       'aria-label' => $title,
                                       'data' => [
                                          'toggle' => 'tooltip',
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
                'attribute' => 'add_time',
            ],
            [
                'attribute' => 'product_id',
                'content' => function($data) {
                    return Html::a($data->product->product_name, [$data->product->productLink], ['target' => '_blank']);
                }
            ],
            [
                'attribute' => 'reviewer',
                'content' => function($data) {
                    return $data->user_id ? Html::a($data->reviewer, ['/manage/access/users/edit/', 'id' => $data->user_id], ['target' => '_blank']) : $data->reviewer;
                }
            ],
            [
                'attribute' => 'review_text',
            ],
            [
                'attribute' => 'rate',
            ],
/*
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

<div class="btn-group" role="group">
    <?= Html::a(new Icon('check').' '.Yii::t('app', 'Active records'), ['/manage/market/reviews'], ['class' => 'btn btn-round btn-'.($status != $page_model::STATUS_DELETED ? 'success' : 'default')]) ?>
    <?= Html::a(new Icon('trash').' '.Yii::t('app', 'Deleted records'), ['/manage/market/reviews', 'status' => $page_model::STATUS_DELETED], ['class' => 'btn btn-round btn-'.($status == $page_model::STATUS_DELETED ? 'success' : 'default')]) ?>
</div>
