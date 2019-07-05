<?php

namespace app\modules\manage\controllers\market;

use Yii;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\web\Response;
use yii\web\UploadedFile;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception as ReaderException;
use app\modules\manage\controllers\AbstractManageController;

class StockImportController extends AbstractManageController
{
    protected $__model = 'app\models\ActiveRecord\Product';

    protected $cat_cache = [];

    public function actionIndex()
    {
        return $this->render('index', [
            'model' => $this->__model,
        ]);
    }

    public function actionUpload()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $sheet = UploadedFile::getInstance($this->__model, 'upload');

        $ext = strtolower($sheet->extension);

        if ($sheet) {
            if ($ext != 'xsl' && $ext != 'xlsx' && $ext != 'xls') {
                return [
                        'status' => 'error',
                        'message' => Yii::t('app', 'The file must be with the extension "{ext}"', ['ext' => 'xls, xsl, xlsx']).' '.$ext,
                      ];
            }

            $directory = $this->__model->uploadFolder.DIRECTORY_SEPARATOR.'import';

            if (!is_dir($directory)) {
                FileHelper::createDirectory($directory);
            }


            $fileName = $this->__model::generateFilename($this->__model->upload).'.'.$ext;
            $filePath = $directory .DIRECTORY_SEPARATOR. $fileName;

            while (file_exists($filePath)) {
                sleep(1);
                $fileName = $this->__model::generateFilename($this->__model->upload).'.'.$ext;
                $filePath = $directory .DIRECTORY_SEPARATOR. $fileName;
            }

            $webPath  = str_replace(Yii::getAlias('@webroot'), '', $filePath);

            if ($sheet->saveAs($filePath)) {

                //$this->actionProcess($fileName);

                return [
                    'status'   => 'ok',
                    'message' => $fileName,
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

    public function actionProcess($filename, $page = 1)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        try {
            $limit = 50;
            $offset = ($page - 1) * $limit;
            $limit  += $offset;
            /** Load $inputFileName to a Spreadsheet Object  **/
            $directory = $this->__model->uploadFolder.DIRECTORY_SEPARATOR.'import';
            $filePath = $directory .DIRECTORY_SEPARATOR.$filename;
            $spreadsheet = IOFactory::load($filePath);
            $oCells = $spreadsheet->getActiveSheet()->getCellCollection();
            $highestRow = $oCells->getHighestRow();

            if ($limit < $highestRow) {
                $next_page = $page + 1;
            } else {
                $limit = $highestRow;
                $next_page = false;
            }

            for ($iRow = $offset + 1; $iRow <= $limit; $iRow++) {
                $extCodeCell = $oCells->get('B'.$iRow);

                if ($extCodeCell) {
                    $ext_code = trim($extCodeCell->getValue());

                    if (mb_strpos($ext_code, 'тов-') === 0) {
                        $product = $this->__model::findOne(['ext_code' => $ext_code]);

                        if ($product) {
                            $product->status   = $product::STATUS_ACTIVE;
                            $product->quantity = $product->quantity ? $product->quantity : 1;

                            $priceCell = $oCells->get('G'.$iRow);

                            if ($priceCell) {
                                $price = trim($priceCell->getValue());
                                $product->price    = str_replace([' ', ',', 'руб', 'руб.'], ['', '.', '', ''], $price);
                            }

                            $product->save();
                        }
                    }
                }
            }

            return [
                'next_page' => $next_page,
                'progress'  => round(($limit / $highestRow) * 100),
            ];

        } catch (ReaderException $e) {
            die('Error loading file: '.$e->getMessage());
        }
    }
}