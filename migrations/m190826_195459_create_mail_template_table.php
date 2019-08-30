<?php

use yii\db\Migration;

/**
 * Handles the creation of table `mail_template`.
 */
class m190826_195459_create_mail_template_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('mail_template', [
            'id' => $this->primaryKey(),
            'status' => $this->integer(1)->defaultValue(0),
            'template_name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
            'content' => $this->text()->defaultValue(NULL),
            'settings' => $this->text()->defaultValue(NULL),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('mail_template');
    }
}
