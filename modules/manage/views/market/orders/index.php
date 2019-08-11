<?php

use yii\helpers\Html;
use rmrevin\yii\fontawesome\component\Icon;
use yiister\gentelella\widgets\grid\GridView;
use app\models\ActiveRecord\ShopOrder;
use app\models\ActiveRecord\ShopOrderPosition;

$this->title = Yii::t('app', $page_model->entitiesName);

?>

<?= GridView::widget(
    [
        'dataProvider' => $data_provider,
        'filterModel' => $search,
        'hover' => true,
        'emptyText' => '<div class="well text-center"><h3>'.Yii::t('app', 'No records found').'</h3></div>',
        'columns' => [
            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['width' => '50', 'class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center', 'style' => 'vertical-align: middle'],
                'template' => ($rules[$page_model->modelName]['is_edit'] ? '{edit} ' : ' ').
                              ($rules[$page_model->modelName]['is_delete'] ? '{delete} ' : ' '),
                'buttons' => [
                    'edit' => function ($url, $model) {
                        return Html::a(new Icon('pencil', ['class' => 'fa-lg']),
                                      ['edit', 'id' => $model->id],
                                      ['title' => Yii::t('app', 'Edit'),
                                       'aria-label' => Yii::t('app', 'Edit'),
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
                'label' => 'Номер заказа',
                'headerOptions' => ['width' => '10'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'status',
                'headerOptions' => ['width' => '10'],
                'contentOptions' => ['class' => 'text-center'],
                'content' => function($data) {
                    if ($data->status == $data::STATUS_DELETED) {
                        return new Icon('trash', [
                            'class' => 'fa-lg',
                            'title' => $data->statuses[$data->status],
                            'aria-label' => $data->statuses[$data->status],
                            'data' => [
                                'toggle' => 'tooltip',
                            ]
                        ]);
                    }

                    if ($data->status == $data::STATUS_INACTIVE) {
                        return new Icon('ban', [
                            'class' => 'fa-lg',
                            'title' => $data->statuses[$data->status],
                            'aria-label' => $data->statuses[$data->status],
                            'data' => [
                                'toggle' => 'tooltip',
                            ]
                        ]);
                    }

                    if ($data->status == $data::STATUS_ACTIVE) {
                        return new Icon('exclamation-circle', [
                            'class' => 'fa-lg',
                            'title' => $data->statuses[$data->status],
                            'aria-label' => $data->statuses[$data->status],
                            'data' => [
                                'toggle' => 'tooltip',
                            ]
                        ]);
                    }

                    if ($data->status == $data::STATUS_WAIT_PAYMENT) {
                        return new Icon('money', [
                            'class' => 'fa-lg',
                            'title' => $data->statuses[$data->status],
                            'aria-label' => $data->statuses[$data->status],
                            'data' => [
                                'toggle' => 'tooltip',
                            ]
                        ]);
                    }

                    if ($data->status == $data::STATUS_READY) {
                        return new Icon('cubes', [
                            'class' => 'fa-lg',
                            'title' => $data->statuses[$data->status],
                            'aria-label' => $data->statuses[$data->status],
                            'data' => [
                                'toggle' => 'tooltip',
                            ]
                        ]);
                    }

                    if ($data->status == $data::STATUS_COMPLETED) {
                        return new Icon('check', [
                            'class' => 'fa-lg',
                            'title' => $data->statuses[$data->status],
                            'aria-label' => $data->statuses[$data->status],
                            'data' => [
                                'toggle' => 'tooltip',
                            ]
                        ]);
                    }
                },
            ],
            [
                'attribute' => 'add_time',
                'content' => function($data) {
                    return $data->getPageTime($data->add_time);
                },
            ],
            [
                'attribute' => 'fio',
            ],
            [
                'attribute' => 'phone',
                'label' => Yii::t('app', 'Phone'),
            ],
            [
                'attribute' => 'email',
                'label' => 'Email',
            ],
            [
                'attribute' => 'address',
            ],
            [
                'encodeLabel' => false,
                'label' => 'Количество позиций<br />Сумма',
                'content' => function($data) {
                     $q = ShopOrderPosition::find()->where(['order_id' => $data->id]);
                     return $q->count().'<br />'.Yii::$app->formatter->asDecimal($q->sum('price * quantity'), 2).' <i class="fa fa-ruble"></i>';
                }
            ],
            [
                'encodeLabel' => false,
                'label' => 'Оплата<br />Доставка',
                'content' => function($data) {
                     return $data->payment_types[$data->payment_type].'<br />'.$data->delivery_types[$data->delivery_type];
                }
            ]
        ],
    ]
);

?>

<div class="btn-group" role="group">
    <?= Html::a(new Icon('check').' '.Yii::t('app', 'Active records'), ['/manage/market/orders'], ['class' => 'btn btn-round btn-'.($status != ShopOrder::STATUS_DELETED?'success':'default')]) ?>
    <?= Html::a(new Icon('trash').' '.Yii::t('app', 'Deleted records'), ['/manage/market/orders', 'status' => ShopOrder::STATUS_DELETED], ['class' => 'btn btn-round btn-'.($status == ShopOrder::STATUS_DELETED?'success':'default')]) ?>
</div>
