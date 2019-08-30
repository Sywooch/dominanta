<?php

namespace app\models\ActiveRecord;

use Yii;
use app\models\ActiveRecord\AbstractModel;
use app\models\Service\Sitemap;

/**
 * This is the model class for table "page".
 *
 * @property int $id
 * @property int $status
 * @property int $pid
 * @property int $template_id
 * @property string $page_name
 * @property string $title
 * @property string $slug
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $page_content
 * @property string $settings
 * @property string $last_update
 * @property int $sitemap_inc
 *
 * @property Page $p
 * @property Page[] $pages
 * @property Template $template
 */
class Page extends AbstractModel
{
    public static $entityName = 'Page';

    public static $entitiesName = 'Pages';

    public $sitemap = true;

    public $page_extension;

    public $product_id;

    public $photo;

    /**
     *
     */
    public function __construct($config = [])
    {
        $ret = parent::__construct($config);
        $this->page_extension = trim(strval(Option::getByKey('page_extension')));
        return $ret;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'pid', 'template_id', 'sitemap_inc', 'page_order'], 'integer'],
            [['slug', 'page_name'], 'required', 'on' => self::SCENARIO_FORM],
            ['slug', 'match', 'pattern' => '/^[A-z0-9_-]*$/i', 'on' => self::SCENARIO_FORM],
            ['slug', 'uniqueSlugValidator', 'on' => self::SCENARIO_FORM],
            [['meta_keywords', 'meta_description', 'page_content', 'settings'], 'string'],
            [['title', 'slug', 'page_name'], 'string', 'max' => 255],
            [['pid'], 'exist', 'skipOnError' => true, 'targetClass' => Page::className(), 'targetAttribute' => ['pid' => 'id']],
            [['template_id'], 'exist', 'skipOnError' => true, 'targetClass' => Template::className(), 'targetAttribute' => ['template_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'status' => Yii::t('app', 'Published'),
            'pid' => Yii::t('app', 'Parent page'),
            'template_id' => Yii::t('app', 'Template'),
            'title' => Yii::t('app', 'Title'),
            'page_name' => Yii::t('app', 'Page name'),
            'slug' => Yii::t('app', 'Slug'),
            'meta_keywords' => Yii::t('app', 'Meta Keywords'),
            'meta_description' => Yii::t('app', 'Meta Description'),
            'page_content' => Yii::t('app', 'Page content'),
            'settings' => Yii::t('app', 'Settings'),
            'sitemap_inc' => Yii::t('app', 'Include to sitemap.xml'),
            'page_order' => Yii::t('app', 'Page order'),
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Page::className(), ['id' => 'pid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPages()
    {
        return $this->hasMany(Page::className(), ['pid' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubpagesCount($status = NULL)
    {
        if ($status == NULL) {
            $status = self::STATUS_ACTIVE;
            $cond = ['!=', 'status', self::STATUS_DELETED];
        } else {
            $cond = ['status' => $status];
        }

        return self::find()->where(['pid' => $this->id])->andWhere($cond)->count();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate()
    {
        return $this->hasOne(Template::className(), ['id' => 'template_id']);
    }

    public function getAbsoluteUrl($with_ext = true)
    {
        return $this->parentUrl.$this->slug.($with_ext ? $this->page_extension : '');
    }

    public function getParentUrl()
    {
        $url = '/';

        if ($this->parent) {
            $url = $this->parent->getAbsoluteUrl(false).$url;
        }

        return $url;
    }

    public function getBreadcrumbs()
    {
        $links[] = [
            'name' => $this->page_name,
            'link' => $this->status == self::STATUS_ACTIVE ? $this->absoluteUrl : false,
        ];

        if ($this->parent) {
            $parent_links = $this->parent->breadcrumbs;

            foreach ($parent_links AS $parent_link) {
                $links[] = $parent_link;
            }
        }

        return $links;
    }

    public function updatePage($sitemap = true)
    {
        $this->sitemap = $sitemap;
        $this->last_update  = self::getDbTime();
        $pages = self::find()->where(['like', 'page_content', '{{{page|'.$this->id.'}}}'])->all();

        foreach ($pages AS $parent_page) {
            $parent_page->updatePage(false);
            $parent_page->save();
        }
    }

    public function sitemap()
    {
        if ($this->sitemap) {
            $sitemap = new Sitemap;
            $sitemap->generate();
        }
    }

    public function eventBeforeInsert()
    {
        $this->create_time = $this->dbTime;

        if (!$this->page_order) {
            $q = self::find()->where(['status' => self::STATUS_ACTIVE]);

            if ($this->pid) {
                $q->andWhere(['pid' => $this->pid]);
            } else {
                $q->andWhere(['pid' => NULL]);
            }

            $this->page_order = $q->count() + 1;
        }

        $this->eventBeforeUpdate();
    }

    public function eventBeforeUpdate()
    {
        $this->page_content = $this->saveContentImages($this->page_content);
        $this->updatePage();
    }

    public function eventAfterInsert()
    {
        $this->sitemap();
    }

    public function eventAfterUpdate()
    {
        $this->sitemap();
    }

    public function eventAfterDelete()
    {
        $this->sitemap();
    }

    public static function findByAddress($page, $only_active = false)
    {
        $page_parts = explode('/', trim($page, '/'));
        $params = [];
        $page_extension = Option::getByKey('page_extension');

        for ($i = count($page_parts) - 1; $i >= 0; $i--) {
            if ($i == count($page_parts) - 1 && $page_extension) {
                $find_slug = str_replace($page_extension, '', $page_parts[$i]);

                if ($find_slug == $page_parts[$i]) {
                    return false;
                }
            } else {
                $find_slug = $page_parts[$i];
            }


            $query = self::find()->where(['slug' => $find_slug]);

            if ($only_active) {
                $query->andWhere(['status' => self::STATUS_ACTIVE]);
            }

            $found = $query->all();
            if (!$found) {
                $params[] = $page_parts[$i];
                continue;
            }

            $parents = [];

            for ($p = $i - 1; $p >= 0; $p--) {
                $parents[] = $page_parts[$p];
            }

            foreach ($found AS $one_page) {
                if ($one_page->absoluteUrl == '/'.implode('/', $page_parts)) {
                    return $one_page;
                    break;
                } else {
                    continue;
                }

                if ($parents) {
                    $check_parent = $one_page;
                    $found_parent = false;

                    foreach ($parents AS $one_parent) {
                        if ($check_parent->parent->slug == $one_parent) {
                            $check_parent = $check_parent->parent;
                            $found_parent = true;
                        } else {
                            $found_parent = false;
                            break;
                        }
                    }

                    if ($found_parent) {
                        return $one_page;
                    }
                } elseif (!$one_page->pid) {
                    return $one_page;
                }
            }
        }
    }

    public function getPageCss()
    {
        return PageCss::find()->where(['page_id' => $this->id])->indexBy('css_id')->orderBy(['s_order' => SORT_ASC])->all();
    }

    public function getPageJs()
    {
        return PageJs::find()->where(['page_id' => $this->id])->indexBy('js_id')->orderBy(['s_order' => SORT_ASC])->all();
    }
}
