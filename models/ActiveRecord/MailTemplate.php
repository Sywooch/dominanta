<?php

namespace app\models\ActiveRecord;

use Yii;
use app\models\ActiveRecord\AbstractModel;

/**
 * This is the model class for table "mail_template".
 *
 * @property int $id
 * @property int $status
 * @property string $template_name
 * @property string $slug
 * @property string $content
 * @property string $settings
 */
class MailTemplate extends AbstractModel
{
    public static $entityName = 'Mail template';

    public static $entitiesName = 'Mail templates';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['template_name', 'slug', 'content'], 'required', 'on' => self::SCENARIO_FORM],
            [['content', 'settings'], 'string'],
            [['template_name', 'slug'], 'string', 'max' => 255],
            ['content', 'match', 'pattern' => '/\\{\\{\\{content\\}\\}\\}/', 'message' => Yii::t('app', 'You need to add the value of {{{content}}} to the template content')]
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
            'template_name' => Yii::t('app', 'Template name'),
            'slug' => Yii::t('app', 'Template ID'),
            'content' => Yii::t('app', 'Content'),
            'settings' => Yii::t('app', 'Settings'),
        ];
    }
}
