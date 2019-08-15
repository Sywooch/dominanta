<?php

use yii\db\Migration;

/**
 * Handles the creation of table `feedback`.
 */
class m190815_202836_create_feedback_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('feedback', [
            'id' => $this->primaryKey(),
            'add_time' => $this->datetime()->defaultValue(NULL),
            'f_name' => $this->string(),
            'phone' => $this->string()->defaultValue(NULL),
            'email' => $this->string()->defaultValue(NULL),
            'message' => $this->text()->defaultValue(NULL),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('feedback');
    }
}
