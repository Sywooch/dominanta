<?php

use yii\db\Migration;

/**
 * Handles the creation of table `page_css`.
 * Has foreign keys to the tables:
 *
 * - `page`
 * - `css`
 */
class m190503_172404_create_page_css_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('page_css', [
            'id' => $this->primaryKey(),
            'page_id' => $this->integer()->defaultValue(NULL),
            'css_id' => $this->integer()->defaultValue(NULL),
            'position' => $this->string()->notNull()->defaultValue(''),
            's_order' => $this->integer()->notNull()->defaultValue(0),
        ]);

        // creates index for column `page_id`
        $this->createIndex(
            'idx-page_css-page_id',
            'page_css',
            'page_id'
        );

        // add foreign key for table `page`
        $this->addForeignKey(
            'fk-page_css-page_id',
            'page_css',
            'page_id',
            'page',
            'id',
            'CASCADE'
        );

        // creates index for column `css_id`
        $this->createIndex(
            'idx-page_css-css_id',
            'page_css',
            'css_id'
        );

        // add foreign key for table `css`
        $this->addForeignKey(
            'fk-page_css-css_id',
            'page_css',
            'css_id',
            'css',
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
            'fk-page_css-page_id',
            'page_css'
        );

        // drops index for column `page_id`
        $this->dropIndex(
            'idx-page_css-page_id',
            'page_css'
        );

        // drops foreign key for table `css`
        $this->dropForeignKey(
            'fk-page_css-css_id',
            'page_css'
        );

        // drops index for column `css_id`
        $this->dropIndex(
            'idx-page_css-css_id',
            'page_css'
        );

        $this->dropTable('page_css');
    }
}
