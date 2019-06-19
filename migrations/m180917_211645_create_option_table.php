<?php

use yii\db\Migration;

/**
 * Handles the creation of table `option`.
 */
class m180917_211645_create_option_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('option', [
            'id' => $this->primaryKey(),
            'option' => $this->string()->notNull()->defaultValue(''),
            'option_name' => $this->string()->notNull()->defaultValue(''),
            'option_value' => $this->string()->notNull()->defaultValue(''),
        ]);

        $options = [
            [
               'option' => 'site_title',
               'option_name' => 'Site title',
               'option_value' => '',
            ],
            [
               'option' => 'site_title_position',
               'option_name' => 'Site title position',
               'option_value' => 'before',
            ],
            [
               'option' => 'site_title_separator',
               'option_name' => 'Site title separator',
               'option_value' => '',
            ],
            [
               'option' => 'main_page',
               'option_name' => 'Main page',
               'option_value' => 'index',
            ],
            [
               'option' => 'page_extension',
               'option_name' => 'Page extension',
               'option_value' => '',
            ],
            [
               'option' => 'scheme',
               'option_name' => 'Scheme',
               'option_value' => 'http',
            ]
        ];

        foreach ($options AS $option) {
            $this->insert('option', $option);
        }
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('option');
    }
}
