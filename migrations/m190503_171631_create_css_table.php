<?php

use yii\db\Migration;

/**
 * Handles the creation of table `css`.
 */
class m190503_171631_create_css_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('css', [
            'id' => $this->primaryKey(),
            'css_name' => $this->string()->notNull(),
            'path' => $this->string()->defaultValue(NULL),
            'content' => $this->text()->defaultValue(NULL),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('css');
    }
}
