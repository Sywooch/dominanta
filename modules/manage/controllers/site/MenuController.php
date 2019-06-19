<?php

namespace app\modules\manage\controllers\site;

use Yii;
use app\modules\manage\controllers\AbstractManageController;

class MenuController extends AbstractManageController
{
    protected $__model = 'app\models\ActiveRecord\Menu';

    public function actionIndex()
    {
        $items = $this->__model->find()->indexBy('id')->orderBy(['item_order' => SORT_ASC])->all();

        if (Yii::$app->request->isPost) {
            $itemsLoad = Yii::$app->request->post($this->__model->modelName, []);

            foreach ($itemsLoad AS $item) {
                if (isset($item['id'])) {
                    $model = $items[$item['id']];
                    unset($items[$item['id']]);
                } else {
                    $model = $this->__model->create();
                    $model->item_order = $this->__model->find()->max('item_order') + 1;
                }

                if ($model->load([$this->__model->modelName => $item]) && $model->validate()) {
                    $model->save();
                }
            }

            foreach ($items AS $item) {
                $item->delete();
            }

            Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been saved'));
            return $this->redirect(['/manage/site/menu'], 301);
        }

        return $this->render('index', ['items' => $items, 'model' => $this->__model]);
    }
}