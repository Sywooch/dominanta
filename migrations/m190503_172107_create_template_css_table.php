<?php

use yii\db\Migration;

/**
 * Handles the creation of table `template_css`.
 * Has foreign keys to the tables:
 *
 * - `template`
 * - `css`
 */
class m190503_172107_create_template_css_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('template_css', [
            'id' => $this->primaryKey(),
            'template_id' => $this->integer()->defaultValue(NULL),
            'css_id' => $this->integer()->defaultValue(NULL),
            'position' => $this->string()->notNull()->defaultValue(''),
            's_order' => $this->integer()->notNull()->defaultValue(0),
        ]);

        // creates index for column `template_id`
        $this->createIndex(
            'idx-template_css-template_id',
            'template_css',
            'template_id'
        );

        // add foreign key for table `template`
        $this->addForeignKey(
            'fk-template_css-template_id',
            'template_css',
            'template_id',
            'template',
            'id',
            'CASCADE'
        );

        // creates index for column `css_id`
        $this->createIndex(
            'idx-template_css-css_id',
            'template_css',
            'css_id'
        );

        // add foreign key for table `css`
        $this->addForeignKey(
            'fk-template_css-css_id',
            'template_css',
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
        // drops foreign key for table `template`
        $this->dropForeignKey(
            'fk-template_css-template_id',
            'template_css'
        );

        // drops index for column `template_id`
        $this->dropIndex(
            'idx-template_css-template_id',
            'template_css'
        );

        // drops foreign key for table `css`
        $this->dropForeignKey(
            'fk-template_css-css_id',
            'template_css'
        );

        // drops index for column `css_id`
        $this->dropIndex(
            'idx-template_css-css_id',
            'template_css'
        );

        $this->dropTable('template_css');
    }
}
