<?php

use yii\helpers\Html;
use rmrevin\yii\fontawesome\component\Icon;
use app\models\ActiveRecord\User;

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

function getTree($roles, $rules, $page_model)
{
    foreach ($roles AS $role) {

?>

        <div class="panel panel-default" class="role_panel">
            <div class="panel-heading">
                <h3 class="panel-title">
                <?php if ($role->status == $page_model::STATUS_DELETED && $rules[$page_model->modelName]['is_delete']) { ?>
                    <?= Html::a(new Icon('undo'), ['restore', 'id' => $role->id], [
                        'title' => Yii::t('app', 'Restore'),
                        'style' => 'float: right; margin-left: 5px',
                        'data' => [
                            'toggle' => 'tooltip',
                            'method' => 'post',
                        ],
                    ]) ?>
                <?php } elseif ($rules[$page_model->modelName]['is_delete']) { ?>
                    <?= Html::a(new Icon('remove'), ['delete', 'id' => $role->id], [
                        'title' => Yii::t('app', 'Delete'),
                        'style' => 'float: right; margin-left: 5px',
                        'aria-label' => Yii::t('app', 'Delete'),
                        'data' => [
                            'toggle' => 'tooltip',
                            'pjax' => 0,
                            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                            'method' => 'post'
                        ],
                    ]) ?>
                    <?php if ($rules[$page_model->modelName]['is_add']) { ?>
                        <?= Html::a(new Icon('plus'), ['edit', 'pid' => $role->id], [
                            'title' => Yii::t('app', 'Add'),
                            'style' => 'float: right',
                            'data' => [
                                'tooltip' => 'true',
                                'toggle' => 'modal',
                                'target' => '.bs-example-modal-lg'
                            ],
                        ]) ?>
                    <?php } ?>
                <?php } ?>
                    <?= Html::encode($role->role_name) ?>
                    <?php if ($rules[$page_model->modelName]['is_edit']) { ?>
                        <?= Html::a(new Icon('pencil'), ['edit', 'id' => $role->id], [
                            'title' => Yii::t('app', 'Edit'),
                            'data' => [
                                'toggle' => 'modal',
                                'target' => '.bs-example-modal-lg',
                                'tooltip' => 'true',
                            ],
                        ]) ?>
                    <?php } ?>
                </h3>
            </div>
            <div class="panel-body" class="role_content" id="role_content<?= $role->id ?>" data-id="<?= $role->id ?>">
                <div class="well">
                    <?= Html::a(
                        User::find()->where(['role_id' => $role->id])->count() .' '. Yii::t('app', 'Records'),
                        ['/manage/access/users', 'User' => ['role_id' => 1]]
                    ) ?>
                </div>

<?php

        if ($role->subroles) {
            getTree($role->subroles, $rules, $page_model);
        }

?>

            </div>
        </div>

<?php

    }
}

if ($roles) {

?>

<?php getTree($roles, $rules, $page_model); ?>

<?php } else { ?>

<div class="well text-center">
    <h3>
        <?= Yii::t('app', 'No roles found') ?>
    </h3>
    <div>
        <?= $status == $page_model::STATUS_ACTIVE ? $add_button : '' ?>
    </div>
</div>

<?php } ?>
<div class="btn-group" role="group">
    <?= Html::a(new Icon('check').' '.Yii::t('app', 'Active records'), ['/manage/access/roles/'], ['class' => 'btn btn-round btn-'.($status == $page_model::STATUS_ACTIVE?'success':'default')]) ?>
    <?= Html::a(new Icon('trash').' '.Yii::t('app', 'Deleted records'), ['/manage/access/roles/', 'status' => $page_model::STATUS_DELETED], ['class' => 'btn btn-round btn-'.($status == $page_model::STATUS_DELETED?'success':'default')]) ?>
</div>
