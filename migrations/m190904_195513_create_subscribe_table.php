<?php

use yii\db\Migration;

/**
 * Handles the creation of table `subscribe`.
 */
class m190904_195513_create_subscribe_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('subscribe', [
            'id' => $this->primaryKey(),
            'status' => $this->integer(1)->notNull()->defaultValue(0),
            'mail_subject' => $this->string()->notNull(),
            'mail_text' => $this->text()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('subscribe');
    }
}
