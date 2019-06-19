<?php

namespace app\modules\manage\controllers\site;

use Yii;
use yii\web\View;
use app\modules\manage\controllers\AbstractManageController;
use app\models\ActiveRecord\Css;
use app\models\ActiveRecord\Js;
use app\models\ActiveRecord\TemplateCss;
use app\models\ActiveRecord\TemplateJs;

class TemplatesController extends AbstractManageController
{
    protected $__model = 'app\models\ActiveRecord\Template';

    public function actionIndex($status = NULL)
    {
        $controller_model = $this->__model;

        if ($status === NULL) {
            $status = $controller_model::STATUS_ACTIVE;
        }

        $data_provider = $this->__model->search(['status' => $status], ['defaultOrder' => ['template_name' => SORT_ASC]]);
        return $this->render('index', ['data_provider' => $data_provider, 'search' => $this->__model, 'status' => $status]);
    }


    public function actionEdit($id = 0)
    {
        if ($id) {
            $model = $this->getById($id);
        } else {
            $model = $this->__model->create();
            $model->template_content = "{{{content}}}";
        }

        $model->scenario = $model::SCENARIO_FORM;

        $all_css = Css::find()->indexBy('id')->orderBy(['css_name' => SORT_ASC])->all();
        $all_js  = Js::find()->indexBy('id')->orderBy(['js_name' => SORT_ASC])->all();

        $page_js_positions = [
            View::POS_HEAD => [
                'open_tag'  => '<head>',
                'close_tag' => '</head>',
                'items' => [],
            ],
            View::POS_BEGIN => [
                'open_tag'  => '<body>',
                'close_tag' => 'PAGE CONTENT',
                'items' => [],
            ],
            View::POS_END => [
                'open_tag'  => 'PAGE CONTENT',
                'close_tag' => '</body>',
                'items' => [],
            ],
        ];

        if ($model->id) {
            $page_css = TemplateCss::find()->where(['template_id' => $model->id])->indexBy('css_id')->orderBy(['s_order' => SORT_ASC])->all();
            $page_js  = TemplateJs::find()->where(['template_id' => $model->id])->indexBy('js_id')->orderBy(['position' => SORT_ASC])->orderBy(['s_order' => SORT_ASC])->all();

            foreach ($page_js AS $js_id => $js) {
                $page_js_positions[$js->position]['items'][$js_id] = $js;
            }
        } else {
            $page_css = [];
            $page_js  = [];
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save(false);

            $save_css = Yii::$app->request->post('css', []);

            foreach ($save_css AS $save_css_id => $css_pos) {
                if (isset($page_css[$save_css_id])) {
                    if ($page_css[$save_css_id]->s_order != $css_pos) {
                        $page_css[$save_css_id]->s_order = $css_pos;
                        $page_css[$save_css_id]->save();
                    }

                    unset($page_css[$save_css_id]);
                } else {
                    TemplateCss::createAndSave([
                        'template_id'  => $model->id,
                        'css_id'   => $save_css_id,
                        's_order' => $css_pos,
                    ]);
                }
            }

            foreach ($page_css AS $del_css) {
                $del_css->delete();
            }

            $save_js = Yii::$app->request->post('js', []);

            foreach ($save_js AS $save_js_id => $js_data) {
                $js_data  = explode('|', $js_data);
                $js_pos   = $js_data[0];
                $js_order = $js_data[1];

                if (isset($page_js[$save_js_id])) {
                    if ($page_js[$save_js_id]->s_order != $js_order || $page_js[$save_js_id]->position != $js_pos) {
                        $page_js[$save_js_id]->s_order  = $js_order;
                        $page_js[$save_js_id]->position = $js_pos;
                        $page_js[$save_js_id]->save();
                    }

                    unset($page_js[$save_js_id]);
                } else {
                    TemplateJs::createAndSave([
                        'template_id'  => $model->id,
                        'js_id'    => $save_js_id,
                        'position' => $js_pos,
                        's_order'  => $js_order,
                    ]);
                }
            }

            foreach ($page_js AS $del_js) {
                $del_js->delete();
            }

            Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been saved'));

            if (Yii::$app->request->isPjax) {
                $model = false;
            } else {
                return $this->redirect(['/manage/site/templates'], 301);
            }
        }

        return $this->render('edit', ['model' => $model,
                                      'is_modal' => true,
                                      'all_css' => $all_css,
                                      'all_js' => $all_js,
                                      'page_css' => $page_css,
                                      'page_js' => $page_js,
                                      'page_js_positions' => $page_js_positions]);
    }

    public function actionDelete($id)
    {
        parent::actionDelete($id);
        Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been deleted'));
        return $this->redirect(['/manage/site/templates'], 301);
    }

    public function actionRestore($id)
    {
        parent::actionRestore($id);
        Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been restored'));
        return $this->redirect(['/manage/site/templates'], 301);
    }
}