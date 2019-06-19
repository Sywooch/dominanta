<?php

use yii\db\Migration;

/**
 * Handles the creation of table `page`.
 * Has foreign keys to the tables:
 *
 * - `page`
 * - `template`
 */
class m180926_194855_create_page_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('page', [
            'id' => $this->primaryKey(),
            'status' => $this->integer(1)->notNull()->defaultValue(0),
            'pid' => $this->integer()->defaultValue(NULL),
            'template_id' => $this->integer()->defaultValue(NULL),
            'page_name' => $this->string()->defaultValue(NULL),
            'title' => $this->string()->defaultValue(NULL),
            'slug' => $this->string()->notNull(),
            'meta_keywords' => $this->text()->defaultValue(NULL),
            'meta_description' => $this->text()->defaultValue(NULL),
            'page_content' => $this->text()->defaultValue(NULL),
            'settings' => $this->text()->defaultValue(NULL),
        ]);

        // creates index for column `pid`
        $this->createIndex(
            'idx-page-pid',
            'page',
            'pid'
        );

        // add foreign key for table `page`
        $this->addForeignKey(
            'fk-page-pid',
            'page',
            'pid',
            'page',
            'id',
            'CASCADE'
        );

        // creates index for column `template_id`
        $this->createIndex(
            'idx-page-template_id',
            'page',
            'template_id'
        );

        // add foreign key for table `template`
        $this->addForeignKey(
            'fk-page-template_id',
            'page',
            'template_id',
            'template',
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
            'fk-page-pid',
            'page'
        );

        // drops index for column `pid`
        $this->dropIndex(
            'idx-page-pid',
            'page'
        );

        // drops foreign key for table `template`
        $this->dropForeignKey(
            'fk-page-template_id',
            'page'
        );

        // drops index for column `template_id`
        $this->dropIndex(
            'idx-page-template_id',
            'page'
        );

        $this->dropTable('page');
    }
}
