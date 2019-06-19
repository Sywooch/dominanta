<?php

namespace app\models\ActiveRecord;

use Yii;
use yii\helpers\Url;
use app\models\ActiveRecord;

/**
 * This is the model class for table "menu".
 *
 * @property int $id
 * @property string $item
 * @property string $link
 * @property int $item_order
 * @property int $pid
 *
 * @property ParentMenu $parentMenu
 * @property Subitems[] $subitems
 */
class Menu extends AbstractModel
{
    public static $entityName = 'Menu';

    public static $entitiesName = 'Menu';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item_order', 'pid'], 'integer'],
            [['item', 'link'], 'string', 'max' => 255],
            [['item'], 'required'],
            [['pid'], 'exist', 'skipOnError' => true, 'targetClass' => Menu::className(), 'targetAttribute' => ['pid' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'item' => Yii::t('app', 'Menu item'),
            'link' => Yii::t('app', 'Link'),
            'item_order' => Yii::t('app', 'Item order'),
            'pid' => Yii::t('app', 'Parent menu'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentMenu()
    {
        return $this->hasOne(Menu::className(), ['id' => 'pid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubitems()
    {
        return $this->hasMany(Menu::className(), ['pid' => 'id']);
    }

    public function getSecondLevel()
    {
        return self::find()->where(['pid' => $this->id])->orderBy(['item_order' => SORT_ASC])->all();
    }

    public function getCurrentLink()
    {
        if (strpos($this->link, '#')) {
            $parts = explode('#', $this->link);

            if (Url::to() == $parts[0]) {
                return '#'.$parts[1];
            }
        }

        return $this->link;
    }
}
