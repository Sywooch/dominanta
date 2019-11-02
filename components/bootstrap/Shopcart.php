<?php

namespace app\components\bootstrap;

use Yii;
use yii\base\Component;
use yii\web\Cookie;
use app\models\ActiveRecord\Shopcart as ShopcartModel;

class Shopcart extends Component
{
    private $items = [];

    private $user;

    private $hash;

    /**
     * Initializes this component.
     */
    public function init()
    {
        $response = Yii::$app->response;
        $request = Yii::$app->request;

        $cookies_req  = $request->cookies;
        $cookies_resp = $response->cookies;

        $user_id = NULL;
        $hash = false;

        if (!Yii::$app->user->isGuest) {
            $user_id = Yii::$app->user->identity->id;

            $record_with_hash = ShopcartModel::find()->where(['user_id' => $user_id])
                                                ->limit(1)
                                                ->one();

            if ($record_with_hash) {
                $hash = $record_with_hash['hash'];
            }
        }

        if (!$hash) {
            if ($cookies_req->has(ShopcartModel::$cookiename)) {
                $hash = $cookies_req->get(ShopcartModel::$cookiename)->value;
            } else {
                $hash = Yii::$app->security->generateRandomString();
            }
        }

        $cookies_resp->add(new Cookie([
            'name' => ShopcartModel::$cookiename,
            'value' => $hash,
            'expire' => time() + (60 * 60 * 24 * 30),
        ]));

        if (!$user_id) {
            $record_with_user = ShopcartModel::find()->where(['hash' => $hash])
                                                ->andWhere(['!=', 'user_id', NULL])
                                                ->limit(1)
                                                ->one();

            if ($record_with_user) {
                $user_id = $record_with_user['user_id'];
            }
        }

        if ($user_id) {
            ShopcartModel::updateAll(['user_id' => $user_id], ['hash' => $hash]);
        }

        $this->user = $user_id;
        $this->hash = $hash;

        $this->getItems();
    }

    public function getShopcartData()
    {
        return [
            'user_id' => $this->user,
            'hash'    => $this->hash,
        ];
    }

    public function getItems($update = false)
    {
        if (!$this->items || $update) {
            if ($this->user) {
                $this->items = ShopcartModel::find()->where(['user_id' => $this->user])->indexBy('product_id')->all();
            } else {
                $this->items = ShopcartModel::find()->where(['hash' => $this->hash])->indexBy('product_id')->all();
            }
        }

        return $this->items;
    }
}
