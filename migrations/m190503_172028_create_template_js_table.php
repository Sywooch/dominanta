<?php

use yii\db\Migration;
use yii\web\View;

/**
 * Handles the creation of table `template_js`.
 * Has foreign keys to the tables:
 *
 * - `template`
 * - `js`
 */
class m190503_172028_create_template_js_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('template_js', [
            'id' => $this->primaryKey(),
            'template_id' => $this->integer()->defaultValue(NULL),
            'js_id' => $this->integer()->defaultValue(NULL),
            'position' => $this->string()->notNull()->defaultValue(View::POS_END),
            's_order' => $this->integer()->notNull()->defaultValue(0),
        ]);

        // creates index for column `template_id`
        $this->createIndex(
            'idx-template_js-template_id',
            'template_js',
            'template_id'
        );

        // add foreign key for table `template`
        $this->addForeignKey(
            'fk-template_js-template_id',
            'template_js',
            'template_id',
            'template',
            'id',
            'CASCADE'
        );

        // creates index for column `js_id`
        $this->createIndex(
            'idx-template_js-js_id',
            'template_js',
            'js_id'
        );

        // add foreign key for table `js`
        $this->addForeignKey(
            'fk-template_js-js_id',
            'template_js',
            'js_id',
            'js',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `template`
        $this->dropForeignKey(
            'fk-template_js-template_id',
            'template_js'
        );

        // drops index for column `template_id`
        $this->dropIndex(
            'idx-template_js-template_id',
            'template_js'
        );

        // drops foreign key for table `js`
        $this->dropForeignKey(
            'fk-template_js-js_id',
            'template_js'
        );

        // drops index for column `js_id`
        $this->dropIndex(
            'idx-template_js-js_id',
            'template_js'
        );

        $this->dropTable('template_js');
    }
}
