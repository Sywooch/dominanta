<?php

namespace app\modules\manage\controllers\site;

use Yii;
use yii\helpers\FileHelper;
use yii\web\Response;
use yii\web\View;
use yii\web\UploadedFile;
use app\modules\manage\controllers\AbstractManageController;
use app\models\ActiveRecord\Css;
use app\models\ActiveRecord\Js;
use app\models\ActiveRecord\PageCss;
use app\models\ActiveRecord\PageJs;

class PagesController extends AbstractManageController
{
    protected $__model = 'app\models\ActiveRecord\Page';

    public function actionIndex($status = NULL, $pid = NULL)
    {
        $controller_model = $this->__model;

        if ($status == $controller_model::STATUS_DELETED) {
            $cond = ['status' => $status, 'pid' => $pid];
        } else {
            $cond = [
                'query' => [
                    ['!=', 'status', $controller_model::STATUS_DELETED],
                    ['pid' => $pid],
                ]
            ];
        }

        $data_provider = $this->__model->search($cond, ['defaultOrder' => ['id' => SORT_ASC]]);
        return $this->render('index', ['data_provider' => $data_provider, 'search' => $this->__model, 'status' => $status, 'pid' => $pid]);
    }


    public function actionEdit($id = 0)
    {
        if ($id) {
            $model = $this->getById($id);
        } else {
            $model = $this->__model->create();
            //$model->status = $model::STATUS_INACTIVE;
            //$model->sitemap_inc = 0;
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
            $page_css = PageCss::find()->where(['page_id' => $model->id])->indexBy('css_id')->orderBy(['s_order' => SORT_ASC])->all();
            $page_js  = PageJs::find()->where(['page_id' => $model->id])->indexBy('js_id')->orderBy(['position' => SORT_ASC])->orderBy(['s_order' => SORT_ASC])->all();

            foreach ($page_js AS $js_id => $js) {
                $page_js_positions[$js->position]['items'][$js_id] = $js;
            }
        } else {
            $page_css = [];
            $page_js  = [];
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
          /*  if (!$model->validate()) {
                print_r($model->rules());
                die();
            }*/

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
                    PageCss::createAndSave([
                        'page_id'  => $model->id,
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
                    PageJs::createAndSave([
                        'page_id'  => $model->id,
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
                return $this->redirect(['/manage/site/pages'], 301);
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
        return $this->redirect(['/manage/site/pages'], 301);
    }

    public function actionRestore($id)
    {
        parent::actionRestore($id);
        Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been restored'));
        return $this->redirect(['/manage/site/pages'], 301);
    }

    public function actionAddress()
    {
        $id = Yii::$app->request->get('page_id', false);

        if ($id !== false && $id !== '') {
            return $this->getById($id)->absoluteUrl;
        } else {
            return '/';
        }
    }

    public function actionPhoto($id)
    {
        $model = $this->getById($id);
        $directory = $model->uploadFolder;

        $current_photo = $directory.DIRECTORY_SEPARATOR.$id.'.jpg';

        if (Yii::$app->request->isPost) {
            $save_photo = Yii::$app->request->post('photo', false);

            if ($save_photo) {
                if ($save_photo != $id) {
                    $old_preview = $model->getPreview($directory.DIRECTORY_SEPARATOR.$save_photo.'.jpg', 450, 150);

                    if (file_exists($old_preview)) {
                        unlink($old_preview);
                    }

                    rename($directory.DIRECTORY_SEPARATOR.$save_photo.'.jpg', $current_photo);
                    $model->getPreview($current_photo, 450, 150, true);
                    $model->getPreview($current_photo, 1440, 500, true);
                }
            } elseif (file_exists($current_photo)) {
                $preview = $model->getPreview($current_photo, 450, 150);

                if (file_exists($preview)) {
                    unlink($preview);
                }

                unlink($current_photo);
            }


            if (Yii::$app->request->isPjax) {
                $model = false;
            } else {
                return $this->redirect(['/manage/site/pages', 'pid' => $model->pid], 301);
            }
        }

        return $this->render('photo', [
            'model' => $model,
            'is_modal' => true,
            'current_photo' => $current_photo,
        ]);
    }

    public function actionUpload($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $page = $this->getById($id);
        $photo = UploadedFile::getInstance($page, 'photo');

        $ext = str_replace('.jpeg', 'jpg', strtolower($photo->extension));

        if ($photo) {
            if ($ext != 'jpg') {
                return [
                        'status' => 'error',
                        'message' => Yii::t('app', 'The file must be with the extension "{ext}"', ['ext' => '.jpg']),
                      ];
            }

            $directory = $page->uploadFolder;

            if (!is_dir($directory)) {
                FileHelper::createDirectory($directory);
            }


            $photoId  = $page::generateFilename($page->photo);
            $fileName = $photoId.'.'.$ext;
            $filePath = $directory .DIRECTORY_SEPARATOR. $fileName;
            $webPath  = str_replace(Yii::getAlias('@webroot'), '', $filePath);

            if ($photo->saveAs($filePath)) {
                return [
                    'files' => [
                        [
                            'fname' => $photoId.'.'.$ext,
                            'thumbnail' => str_replace(Yii::getAlias('@webroot'), '', $page->getPreview($filePath, 450, 150)),
                            'photo_id' => $photoId,
                        ],
                    ],
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => Yii::t('app', 'Error saving file'),
                ];
            }
        }

        return [
            'status' => 'error',
            'message' => Yii::t('app', 'File upload error'),
        ];
    }
}