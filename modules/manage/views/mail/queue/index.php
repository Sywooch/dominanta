<?php

use yii\helpers\Html;
use rmrevin\yii\fontawesome\component\Icon;
use yiister\gentelella\widgets\grid\GridView;
use app\models\ActiveRecord\MailSetting;
use app\models\ActiveRecord\Mail;

$this->title = Yii::t('app', $page_model->entitiesName);

?>

<?= GridView::widget(
    [
        'dataProvider' => $data_provider,
        'filterModel' => $search,
        'hover' => true,
        'emptyText' => '<div class="well text-center"><h3>'.Yii::t('app', 'No records found').'</h3><div></div></div>',
        'columns' => [
            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['width' => '80', 'class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center', 'style' => 'vertical-align: middle'],
                'template' => ($rules[$page_model->modelName]['is_edit'] ? '{resend}' : '').' '.
                              ($rules[$page_model->modelName]['is_delete'] ? '{delete}' : ''),
                'buttons' => [
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
                    'resend' => function ($url, $model) {
                        return ($model->status == $model::STATUS_ERROR) ?
                                Html::a(new Icon('refresh', ['class' => 'fa-lg']),
                                ['resend', 'id' => $model->id],
                                ['title' => Yii::t('app', 'Resend'),
                                 'aria-label' => Yii::t('app', 'Resend'),
                                 'data' => [
                                   'toggle' => 'tooltip',
                                   'pjax' => 0,
                                   'method' => 'post',
                                ]])
                                : '';
                    }
                 ],
            ],
            [
                'attribute' => 'create_time',
                'content' => function($data) {
                    return $data->getPageFormatTime('create_time');
                }
            ],
            [
                'attribute' => 'status',
                'content' => function($data) {
                    return new Icon($data->statusIcon, ['class' => 'fa-lg', 'data-toggle' => 'tooltip', 'title' => $data->statusText, 'aria-label' => $data->statusText]);
                }
            ],
            'to_email',
            'subject',
            [
                'attribute' => 'send_time',
                'content' => function($data) {
                    return $data->send_time !== NULL ? $data->getPageFormatTime('send_time') : '';
                }
            ],
            [
                'attribute' => 'body_text',
                'content' => function($data) {
                    return Html::a(Yii::t('app', 'Body text'), '#', ['onclick' => 'getBodyMessage('.$data->id.'); return false']).'<div style="display: none" id="body_text_'.$data->id.'">'.str_replace(["\n", "\r"], ['<br />', ''], ($data->body_html ? $data->body_html : $data->body_text)).'</div>';
                }
            ],
            [
                'attribute' => 'send_errors',
                'content' => function($data) {
                    return $data->send_errors ? Html::a(Yii::t('app', 'Send errors'), '#', ['onclick' => 'getSendError('.$data->id.'); return false']).'<div style="display: none" id="send_error_'.$data->id.'">'.str_replace(["\n", "\r"], ['<br />', ''], $data->send_errors).'</div>':'';
                }
            ],
        ],
    ]
);

?>

<script type="text/javascript">
  function getBodyMessage(id) {
      $('.modal-body').html($('#body_text_' + id).html());
      $('#admin_modal_window').modal('show');
      $('#myModalLabel').html('<?= Yii::t('app', 'Body text') ?>');
  }

  function getSendError(id) {
      $('.modal-body').html($('#send_error_' + id).html());
      $('#admin_modal_window').modal('show');
      $('#myModalLabel').html('<?= Yii::t('app', 'Error') ?>');
  }

</script>

<div class="btn-group" role="group">

</div>
