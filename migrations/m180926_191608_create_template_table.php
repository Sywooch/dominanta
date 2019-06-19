<?php

use yii\db\Migration;

/**
 * Handles the creation of table `template`.
 */
class m180926_191608_create_template_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('template', [
            'id' => $this->primaryKey(),
            'status' => $this->integer(1)->notNull()->defaultValue(0),
            'layout' => $this->string()->notNull(),
            'template_name' => $this->string()->notNull(),
            'template_content' => $this->text()->defaultValue(NULL),
            'settings' => $this->text()->defaultValue(NULL),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('template');
    }
}
