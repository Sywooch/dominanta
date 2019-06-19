<?php

use yii\db\Migration;
use yii\web\View;

/**
 * Handles the creation of table `page_js`.
 * Has foreign keys to the tables:
 *
 * - `page`
 * - `js`
 */
class m190503_172504_create_page_js_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('page_js', [
            'id' => $this->primaryKey(),
            'page_id' => $this->integer()->defaultValue(NULL),
            'js_id' => $this->integer()->defaultValue(NULL),
            'position' => $this->string()->notNull()->defaultValue(View::POS_END),
            's_order' => $this->integer()->notNull()->defaultValue(0),
        ]);

        // creates index for column `page_id`
        $this->createIndex(
            'idx-page_js-page_id',
            'page_js',
            'page_id'
        );

        // add foreign key for table `page`
        $this->addForeignKey(
            'fk-page_js-page_id',
            'page_js',
            'page_id',
            'page',
            'id',
            'CASCADE'
        );

        // creates index for column `js_id`
        $this->createIndex(
            'idx-page_js-js_id',
            'page_js',
            'js_id'
        );

        // add foreign key for table `js`
        $this->addForeignKey(
            'fk-page_js-js_id',
            'page_js',
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
        // drops foreign key for table `page`
        $this->dropForeignKey(
            'fk-page_js-page_id',
            'page_js'
        );

        // drops index for column `page_id`
        $this->dropIndex(
            'idx-page_js-page_id',
            'page_js'
        );

        // drops foreign key for table `js`
        $this->dropForeignKey(
            'fk-page_js-js_id',
            'page_js'
        );

        // drops index for column `js_id`
        $this->dropIndex(
            'idx-page_js-js_id',
            'page_js'
        );

        $this->dropTable('page_js');
    }
}
