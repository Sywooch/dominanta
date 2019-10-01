<?php

namespace app\controllers;

use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\models\ActiveRecord\DeliveryAddress;
use app\models\ActiveRecord\Page;
use app\models\ActiveRecord\Product;
use app\models\ActiveRecord\ProductPhoto;
use app\models\ActiveRecord\Shopcart;
use app\models\ActiveRecord\ShopOrder;
use app\models\ActiveRecord\ShopOrderPosition;
use app\models\ActiveRecord\ShopPayment;
use app\models\ActiveRecord\User;

class ShopcartController extends AbstractController
{
    /** @var app\models\ActiveRecord\Page */
    protected $page = false;

    //public $enableCsrfValidation = false;

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'result' => [
                'class' => '\robokassa\ResultAction',
                'callback' => [$this, 'resultCallback'],
            ],
            'success' => [
                'class' => '\robokassa\SuccessAction',
                'callback' => [$this, 'successCallback'],
            ],
            'fail' => [
                'class' => '\robokassa\FailAction',
                'callback' => [$this, 'failCallback'],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if ($action->id == 'result' || $action->id == 'success' || $action->id ==  'fail') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    protected function getPayment($id, $amount)
    {
        $payment = ShopPayment::findOne(['order_id' => $id, 'amount' => $amount, 'status' => ShopPayment::STATUS_INACTIVE]);

        if (!$payment) {
            throw new NotFoundHttpException('Заказ не найден');
        }

        return $payment;
    }

    /**
     * Callback.
     * @param \robokassa\Merchant $merchant merchant.
     * @param integer $nInvId invoice ID.
     * @param float $nOutSum sum.
     * @param array $shp user attributes.
         */
    public function successCallback($merchant, $nInvId, $nOutSum, $shp)
    {
        $payment = ShopPayment::find()->where(['order_id' => $nInvId])
                                      ->andWhere(['amount' => $nOutSum])
                                      ->andWhere(['>=', 'status', ShopPayment::STATUS_INACTIVE])
                                      ->one();

        if (!$payment) {
            throw new NotFoundHttpException('Заказ не найден');
        }

        $payment->payed = $nOutSum;
        $payment->save();

        $payment->order->sendEmails();

        Yii::$app->session->setFlash('success', '<i class="fa fa-check"></i> Заказ №'.$nInvId.' успешно оформлен и оплачен!');
        return $this->redirect(['/shopcart/processed'], 301);
    }

    public function resultCallback($merchant, $nInvId, $nOutSum, $shp)
    {
        $payment = $this->getPayment($nInvId, $nOutSum);
        $payment->status = ShopPayment::STATUS_ACTIVE;
        $payment->save();

        $payment->order->sendPaymentNotify();

        return 'OK' . $nInvId;
    }

    public function failCallback($merchant, $nInvId, $nOutSum, $shp)
    {
        $payment = $this->getPayment($nInvId, $nOutSum);
        $payment->status = ShopPayment::STATUS_DELETED;
        $payment->save();

        Yii::$app->session->setFlash('danger', '<i class="fa fa-ban"></i> Оплата по заказу №'.$nInvId.' отменена!<br />'.Html::a('Попробовать провести оплату снова', '/shopcart/payment?order_id='.$nInvId));
        return $this->redirect(['/shopcart/error'], 301);
    }

    protected function payment()
    {
        $request = Yii::$app->request;
        $order_id = $request->get('order_id', 0);

        $order =  ShopOrder::findOne(['id' => $order_id, 'status' => ShopOrder::STATUS_ACTIVE, 'payment_type' => 0]);

        if (!$order) {
            throw new NotFoundHttpException('Заказ не найден');
        }

        $amount = 0;
        $payed  = 0;

        foreach ($order->shopOrderPosition AS $pos) {
            $amount += $pos->price * $pos->quantity;
        }

        foreach ($order->shopPayments AS $pay) {
            $amount -= $pay->payed;
        }

        if (!$amount) {
            throw new NotFoundHttpException('Заказ не найден');
        }

        $online_payment = ShopPayment::createAndSave([
            'order_id' => $order->id,
            'status' => ShopPayment::STATUS_INACTIVE,
            'amount' => $amount,
            'payed' => 0,
            'hash' => '',
        ]);

        /** @var \robokassa\Merchant $merchant */
        $merchant = Yii::$app->get('robokassa');
        return $merchant->payment($amount, $order->id, 'Оплата заказа №'.$order->id, null, $order->email);
    }

    public static function getShopcartData()
    {
        $response = Yii::$app->response;
        $request = Yii::$app->request;

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
            if ($cookies_req->has(Shopcart::$cookiename)) {
                $hash = $cookies_req->get(Shopcart::$cookiename)->value;
            } else {
                $hash = Yii::$app->security->generateRandomString();
            }
        }

        $cookies_resp->add(new Cookie([
            'name' => Shopcart::$cookiename,
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
            Shopcart::updateAll(['user_id' => $user_id], ['hash' => $hash]);
        }

        return [
            'user_id' => $user_id,
            'hash'    => $hash,
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

            $request = Yii::$app->getRequest();
            $page_content = $this->render('page', [
                'page' => $this->page,
                'controller' => $this,
                'site_options' => $site_options,
                'csrfParam' => $request->csrfParam,
                'csrfToken' => $request->getCsrfToken(),
            ]);

            return $page_content;
        }
    }

    protected function view()
    {
        $shopcart_data = self::getShopcartData();
        $user_id = $shopcart_data['user_id'];
        $hash    = $shopcart_data['hash'];

        if ($user_id) {
            $shopcart = Shopcart::find()->where(['user_id' => $user_id])->all();
        } else {
            $shopcart = Shopcart::find()->where(['hash' => $hash])->all();
        }

        $cnt = 0;
        $sum = 0;

        foreach ($shopcart AS  $idx => $item) {
            if ($item->product->status != Product::STATUS_ACTIVE) {
                $item->delete();
                unset($shopcart[$idx]);
            }

            $cnt++;
            $sum += ($item->product->price - ($item->product->price * ($item->product->discount / 100))) * $item['quantity'];
        }

        $shopcart_form = $shopcart ? $this->getShopcartForm($shopcart) : '';

        if (!is_string($shopcart_form)) {
            return $shopcart_form;
        }

        $this->page = Page::findByAddress('/shopcart/view', false);

        if ($this->page->template) {
            $this->layout = $this->page->template->layout;
        }

        $this->page->title = 'Корзина';

        $site_options = Yii::$app->site_options;
        $request = Yii::$app->getRequest();
        $rendered_page = $this->render('page', [
            'page' => $this->page,
            'controller' => $this,
            'site_options' => $site_options,
            'csrfParam' => $request->csrfParam,
            'csrfToken' => $request->getCsrfToken(),
        ]);

        $replace = [
            '{{{breadcrumbs}}}' => $this->breadcrumbs($this->page->title),
            '{{{page_title}}}' => $this->page->title,
            '{{{shopcart_alert}}}' => $this->getShopcartAlert($shopcart),
            '{{{shopcart_items}}}' => $shopcart ? $this->getShopcartItems($shopcart) : '',
            '{{{shopcart_form}}}' => $shopcart_form,
        ];

        return str_replace(array_keys($replace), $replace, $rendered_page);
    }

    protected function processed()
    {
        $this->page = Page::findByAddress('/shopcart/processed', false);

        if ($this->page->template) {
            $this->layout = $this->page->template->layout;
        }

        $this->page->title = 'Заказ оформлен';

        $site_options = Yii::$app->site_options;
        $request = Yii::$app->getRequest();
        $rendered_page = $this->render('page', [
            'page' => $this->page,
            'controller' => $this,
            'site_options' => $site_options,
            'csrfParam' => $request->csrfParam,
            'csrfToken' => $request->getCsrfToken(),
        ]);

        $replace = [
            '{{{breadcrumbs}}}' => $this->breadcrumbs($this->page->title),
            '{{{page_title}}}' => $this->page->title,
            '{{{messagetype}}}' => 'success',
            '{{{message}}}' => Yii::$app->session->getFlash('success'),
        ];

        return str_replace(array_keys($replace), $replace, $rendered_page);
    }

    protected function error()
    {
        $this->page = Page::findByAddress('/shopcart/processed', false);

        if ($this->page->template) {
            $this->layout = $this->page->template->layout;
        }

        $this->page->title = 'Ошибка оплаты заказа';

        $site_options = Yii::$app->site_options;
        $request = Yii::$app->getRequest();
        $rendered_page = $this->render('page', [
            'page' => $this->page,
            'controller' => $this,
            'site_options' => $site_options,
            'csrfParam' => $request->csrfParam,
            'csrfToken' => $request->getCsrfToken(),
        ]);

        $replace = [
            '{{{breadcrumbs}}}' => $this->breadcrumbs($this->page->title),
            '{{{page_title}}}' => $this->page->title,
            '{{{messagetype}}}' => 'danger',
            '{{{message}}}' => Yii::$app->session->getFlash('danger'),
        ];

        return str_replace(array_keys($replace), $replace, $rendered_page);
    }

    protected function getShopcartAlert($shopcart)
    {
        return '<div class="alert alert-info shopcart_alert'.($shopcart ? ' hidden' : '').'" role="alert">Корзина пуста. Перейдите в '.Html::a('каталог товаров', '/shop').', чтобы сформировать заказ.</div>';
    }

    protected function getShopcartItems($shopcart)
    {
        $html = '';

        foreach ($shopcart AS $item) {
            $html .= $this->renderPartial('item', ['item' => $item]);
        }

        return $html;
    }

    protected function getShopcartForm($shopcart)
    {
        $model = new ShopOrder;
        $model->scenario = $model::SCENARIO_ADD;
        $model->delivery_type = 0;
        $model->payment_type  = 0;

        $addresses = [];

        $sel_address = false;

        if (!Yii::$app->user->isGuest) {
            $model->fio = Yii::$app->user->identity->realname;
            $model->phone = Yii::$app->user->identity->phone;
            $model->email = Yii::$app->user->identity->email;

            $addr_list = DeliveryAddress::find()->where(['user_id' => Yii::$app->user->identity->id])->all();
            $sel_address = Yii::$app->request->post('sel_address', false);

            foreach ($addr_list AS $idx => $one_addr) {
                $selected = ((!Yii::$app->request->isPost && !$sel_address && !$idx) || $one_addr->id == $sel_address);

                if ($selected && !$sel_address) {
                    $sel_address = $one_addr->id;
                }

                $addresses[$one_addr->id] = [
                    'id'       => $one_addr->id,
                    'name'     => $one_addr->address_name,
                    'address'  => $one_addr->address,
                    'selected' => $selected,
                ];
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (!Yii::$app->user->isGuest) {
                $model->user_id = Yii::$app->user->identity->id;

                if ($sel_address) {
                    $model->address = isset($addresses[$sel_address]) ? $addresses[$sel_address]['address'] : '';
                }
            }

            $model->save();
            $amount = 0;

            foreach ($shopcart AS $item) {
                $item_end_price = $item->product->price - ($item->product->price * ($item->product->discount / 100));

                ShopOrderPosition::createAndSave([
                    'order_id'   => $model->id,
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'price'      => $item_end_price,
                ]);

                $item->delete();

                $amount += $item->quantity * $item_end_price;
            }

            if (!$model->payment_type) {
                $online_payment = ShopPayment::createAndSave([
                    'order_id' => $model->id,
                    'status' => ShopPayment::STATUS_INACTIVE,
                    'amount' => $amount,
                    'payed' => 0,
                    'hash' => '',
                ]);

                /** @var \robokassa\Merchant $merchant */
                $merchant = Yii::$app->get('robokassa');
                return $merchant->payment($online_payment->amount, $model->id, 'Оплата заказа №'.$model->id, null, $model->email);
            } else {
                $model->sendEmails();
            }

            Yii::$app->session->setFlash('success', '<i class="fa fa-check"></i> '.Yii::t('app', 'Заказ №'.$model->id.' оформлен!'));
            return $this->redirect(['/shopcart/processed'], 301);
        }

        $total = 0;

        foreach ($shopcart AS $item) {
            $total += ($item->product->price - ($item->product->price * ($item->product->discount / 100))) * $item->quantity;
        }

        if ($sel_address) {
            $model->address = '';
        }

        return $this->renderPartial('form', [
            'model' => $model,
            'addresses' => $addresses,
            'sel_address' => $sel_address,
            'total' => $total,
        ]);
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

        $shopcart_data = self::getShopcartData();
        $user_id = $shopcart_data['user_id'];
        $hash    = $shopcart_data['hash'];

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

    protected function delete()
    {
        $response = Yii::$app->response;
        $response->format = Response::FORMAT_JSON;

        $request = Yii::$app->request;
        $item_id = $request->get('item_id', false);

        if (!$item_id) {
            return [
                'status'  => 'error',
                'message' => 'Invalid product id',
            ];
        }

        $shopcart_data = self::getShopcartData();
        $user_id = $shopcart_data['user_id'];
        $hash    = $shopcart_data['hash'];

        if ($user_id) {
            $item_exists = Shopcart::find()->where(['id' => $item_id])
                                              ->andWhere(['user_id' => $user_id])
                                              ->limit(1)
                                              ->one();
        } else {
            $item_exists = Shopcart::find()->where(['id' => $item_id])
                                              ->andWhere(['hash' => $hash])
                                              ->limit(1)
                                              ->one();
        }

        if ($item_exists) {
            $item_exists->delete();
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
                'id'  => $item_id,
            ]
        ];
    }

    protected function update_cnt()
    {
        $response = Yii::$app->response;
        $response->format = Response::FORMAT_JSON;

        $request = Yii::$app->request;
        $item_id = $request->get('item_id', false);
        $cnt     = intval($request->get('cnt', 0));

        if (!$item_id) {
            return [
                'status'  => 'error',
                'message' => 'Invalid product id',
            ];
        }

        if (!$cnt) {
            return [
                'status'  => 'error',
                'message' => 'Invalid cnt',
            ];
        }

        $shopcart_data = self::getShopcartData();
        $user_id = $shopcart_data['user_id'];
        $hash    = $shopcart_data['hash'];

        if ($user_id) {
            $item_exists = Shopcart::find()->where(['id' => $item_id])
                                              ->andWhere(['user_id' => $user_id])
                                              ->limit(1)
                                              ->one();
        } else {
            $item_exists = Shopcart::find()->where(['id' => $item_id])
                                              ->andWhere(['hash' => $hash])
                                              ->limit(1)
                                              ->one();
        }

        if ($item_exists) {
            $item_exists->quantity = $cnt;
            $item_exists->save();
        } else {
            return [
                'status'  => 'error',
                'message' => 'Item not found',
            ];
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
                'id'  => $item_id,
            ]
        ];
    }

    protected function breadcrumbs($endpoint)
    {
        return Html::a('Главная', '/').
               ' <i class="fa fa-angle-right"></i> '.
               '<span>'.$endpoint.'</span>';
    }
}
