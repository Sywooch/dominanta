<?php

namespace app\models\ActiveRecord;

use Yii;
use yii\helpers\Html;
use app\models\ActiveRecord\AbstractModel;
use app\models\Service\Sitemap;
use app\models\Service\Yml;

/**
 * This is the model class for table "product_category".
 *
 * @property int $id
 * @property int $status
 * @property int $pid
 * @property string $category_name
 * @property string $slug
 * @property string $category_description
 * @property string $title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $last_update
 * @property string $link
 *
 * @property Product[] $products
 * @property ProductCategory $p
 * @property ProductCategory[] $productCategories
 */
class ProductCategory extends AbstractModel
{
    public static $entityName = 'Product category';

    public static $entitiesName = 'Product categories';

    public $count_cache = [];

    public $photo;

    public $disabled_cats = [];

    public $cats_with_products = [];

    public $update_sitemap = true;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'pid'], 'integer'],
            [['category_name', 'slug'], 'required', 'on' => self::SCENARIO_FORM],
            ['slug', 'match', 'pattern' => '/^[A-z0-9_-]*$/i', 'on' => self::SCENARIO_FORM],
            ['slug', 'uniqueSlugValidator', 'on' => self::SCENARIO_FORM],
            [['category_description', 'title', 'meta_keywords', 'meta_description'], 'string'],
            [['last_update'], 'safe'],
            [['category_name', 'slug', 'link'], 'string', 'max' => 255],
            [['pid'], 'exist', 'skipOnError' => true, 'targetClass' => ProductCategory::className(), 'targetAttribute' => ['pid' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'status' => Yii::t('app', 'Status'),
            'pid' => Yii::t('app', 'Parent category'),
            'category_name' => Yii::t('app', 'Category name'),
            'slug' => Yii::t('app', 'Slug'),
            'category_description' => Yii::t('app', 'Category description'),
            'title' => Yii::t('app', 'Title'),
            'meta_keywords' => Yii::t('app', 'Meta keywords'),
            'meta_description' => Yii::t('app', 'Meta description'),
            'last_update' => Yii::t('app', 'Last update'),
            'link' => Yii::t('app', 'Ext link'),
        ];
    }

    public function uniqueSlugValidator($attribute, $params)
    {
        $checkQuery = self::find()->where(['slug' => $this->slug])->andWhere(['pid' => $this->pid == '' ? NULL : $this->pid]);

        if ($this->id) {
            $checkQuery->andWhere(['!=', 'id', $this->id]);
        }

        $find = $checkQuery->one();

        if ($find) {
            $this->addError($attribute, Yii::t('app', 'This value must be unique within a subsection.'));
        }
    }

    public function eventBeforeInsert()
    {
        $checkQuery = self::find()->where(['slug' => $this->slug])->andWhere(['pid' => $this->pid == '' ? NULL : $this->pid]);

        $find = $checkQuery->count();

        if ($find) {
            $this->slug .= '-'.$find;
        }

        $this->eventBeforeUpdate();
    }

    public function eventBeforeUpdate()
    {
        $this->last_update = self::getDbTime();

        if ($this->pid) {
            $this->parentCat->last_update = self::getDbTime();
            $this->parentCat->update_sitemap = $this->update_sitemap;
            $this->parentCat->save();
        }
    }

    public function eventAfterInsert()
    {
        $this->eventAfterUpdate();
    }

    public function eventAfterUpdate()
    {
        if ($this->update_sitemap) {
            $sitemap = new Sitemap;
            $sitemap->generate();

            $yml = new Yml;
            $yml->generate();
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['cat_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentCat()
    {
        return $this->hasOne(ProductCategory::className(), ['id' => 'pid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductCategories()
    {
        return $this->hasMany(ProductCategory::className(), ['pid' => 'id']);
    }

    public function getCountSubcats()
    {
        return self::find()->where(['>=', 'status', self::STATUS_INACTIVE])->andWhere(['pid' => $this->id])->count();
    }

    public function getCountAllSubcats()
    {
        if ($this->count_cache) {
            return $this->count_cache;
        }

        $this->count_cache = [
            'all' => 0,
            'published' => 0,
            'products_all' => Product::find()->where(['>=', 'status', Product::STATUS_INACTIVE])->andWhere(['cat_id' => $this->id])->count(),
            'products_published' => Product::find()->where(['>=', 'status', Product::STATUS_ACTIVE])->andWhere(['cat_id' => $this->id])->count(),
        ];

        $subcats = self::find()->select(['id', 'status'])->where(['>=', 'status', self::STATUS_INACTIVE])->andWhere(['pid' => $this->id])->all();

        foreach ($subcats AS $subcat) {
            $this->count_cache['all']++;

            if ($subcat->status == self::STATUS_ACTIVE) {
                $this->count_cache['published']++;
            }

            $subcat_count = $subcat->getCountAllSubcats();

            $this->count_cache['all'] += $subcat_count['all'];
            $this->count_cache['published'] += $subcat_count['published'];
            $this->count_cache['products_all'] += $subcat_count['products_all'];
            $this->count_cache['products_published'] += $subcat_count['products_published'];
        }

        return $this->count_cache;
    }

    public function getCountProducts()
    {
        return Product::find()->where(['>=', 'status', self::STATUS_INACTIVE])->andWhere(['cat_id' => $this->id])->count();
    }

    public function getListCat($level = 0, $pid = NULL, $filter = NULL)
    {
        $cat_list = [];
        $cats = self::find()->select(['id', 'category_name'])
                                          ->where(['pid' => $pid])
                                          ->andWhere(['>=', 'status', self::STATUS_INACTIVE])
                                          ->orderBy(['category_name' => SORT_ASC])
                                          ->all();

        foreach ($cats AS $one_cat) {
            if ($one_cat->id == $filter) {
                continue;
            }

            $cat_list[$one_cat->id] = str_repeat('- ', $level).Html::encode($one_cat->category_name);

            $subcats_list = $this->getListCat($level + 1, $one_cat->id, $filter);

            if ($subcats_list) {
                $this->disabled_cats[$one_cat->id] = [
                    'disabled' => true,
                ];
            } else {
                if (Product::find()->where(['cat_id' => $one_cat->id])->count()) {
                    $this->cats_with_products[$one_cat->id] = [
                        'disabled' => true,
                    ];
                }
            }

            foreach ($subcats_list AS $subcat_id => $subcat_name) {
                $cat_list[$subcat_id] = $subcat_name;
            }
        }

        return $cat_list;
    }

    public function getCatLink()
    {
        return ($this->pid ? $this->parentCat->catLink : '/shop').'/'.$this->slug;
    }
}
