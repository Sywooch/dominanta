<?php

use yii\db\Migration;

/**
 * Handles the creation of table `js`.
 */
class m190503_171559_create_js_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('js', [
            'id' => $this->primaryKey(),
            'js_name' => $this->string()->notNull(),
            'path' => $this->string()->defaultValue(NULL),
            'content' => $this->text()->defaultValue(NULL),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('js');
    }
}
