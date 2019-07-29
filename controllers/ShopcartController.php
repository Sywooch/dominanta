<?php

namespace app\controllers;

use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\web\Cookie;
use yii\web\Response;
use app\models\ActiveRecord\Page;
use app\models\ActiveRecord\Product;
use app\models\ActiveRecord\ProductPhoto;
use app\models\ActiveRecord\Shopcart;
use app\models\ActiveRecord\User;

class ShopcartController extends AbstractController
{
    /** @var app\models\ActiveRecord\Page */
    protected $page = false;

    protected $cookiename = 'shopcart';

    //public $enableCsrfValidation = false;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            /*'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],*/
        ];
    }

    public function actionIndex($url = '')
    {
        $url = trim($url, '/');

        if ($url == '') {
            $url = 'view';
        }

        $site_options = Yii::$app->site_options;
        $page_extension = isset($site_options->page_extension) ? trim($site_options->page_extension) : '';

        if (method_exists(self::className(), $url)) {
            return $this->$url();
        } else {
            $this->page = Page::findByAddress('/shopcart/'.$url, false);

            if (!$this->page) {
                Yii::$app->response->statusCode = 404;
                $this->page = Page::findByAddress('404'.$page_extension, false);
            }

            if (!$this->page) {
                throw new NotFoundHttpException();
            }
        }
    }

    protected function add()
    {
        $response = Yii::$app->response;
        $response->format = Response::FORMAT_JSON;

        $request = Yii::$app->request;
        $product_id = $request->get('product_id', false);
        $quantity = $request->get('quantity', 1);

        if (!$product_id) {
            return [
                'status'  => 'error',
                'message' => 'Invalid product id',
            ];
        }

        $product = Product::findOne($product_id);

        if (!$product) {
            return [
                'status'  => 'error',
                'message' => 'Product not found',
            ];
        }

        $cookies_req  = $request->cookies;
        $cookies_resp = $response->cookies;

        $user_id = NULL;
        $hash = false;

        if (!Yii::$app->user->isGuest) {
            $user_id = Yii::$app->user->identity->id;

            $record_with_hash = Shopcart::find()->where(['user_id' => $user_id])
                                                ->limit(1)
                                                ->one();

            if ($record_with_hash) {
                $hash = $record_with_hash['hash'];
            }
        }

        if (!$hash) {
            if ($cookies_req->has($this->cookiename)) {
                $hash = $cookies_req->get($this->cookiename);
            } else {
                $hash = Yii::$app->security->generateRandomString();
            }
        }

        $cookies_resp->add(new Cookie([
            'name' => $this->cookiename,
            'value' => $hash,
            'expire' => time() + (60 * 60 * 24 * 30),
        ]));

        if (!$user_id) {
            $record_with_user = Shopcart::find()->where(['hash' => $hash])
                                                ->andWhere(['!=', 'user_id', NULL])
                                                ->limit(1)
                                                ->one();

            if ($record_with_user) {
                $user_id = $record_with_user['user_id'];
            }
        }

        if ($user_id) {
            $product_exists = Shopcart::find()->where(['product_id' => $product_id])
                                              ->andWhere(['user_id' => $user_id])
                                              ->limit(1)
                                              ->one();
        } else {
            $product_exists = Shopcart::find()->where(['product_id' => $product_id])
                                              ->andWhere(['hash' => $hash])
                                              ->limit(1)
                                              ->one();
        }

        if ($product_exists) {
            $product_exists['quantity'] += $quantity;
            $product_exists->save();
        } else {
            Shopcart::createAndSave([
                'hash' => $hash,
                'user_id' => $user_id,
                'product_id' => $product_id,
                'quantity' => $quantity,
            ]);
        }

        if ($user_id) {
            Shopcart::updateAll(['user_id' => $user_id], ['hash' => $hash]);
        }

        if ($user_id) {
            $shopcart = Shopcart::find()->where(['user_id' => $user_id])->all();
        } else {
            $shopcart = Shopcart::find()->where(['hash' => $hash])->all();
        }

        $cnt = 0;
        $sum = 0;

        foreach ($shopcart AS $item) {
            if ($item->product->status != Product::STATUS_ACTIVE) {
                $item->delete();
            }

            $cnt++;
            $sum += ($item->product->price - ($item->product->price * ($item->product->discount / 100))) * $item['quantity'];
        }

        return [
            'status' => 'ok',
            'message' => [
                'cnt' => $cnt,
                'sum' => Yii::$app->formatter->asDecimal($sum, 2),
            ]
        ];
    }

}
